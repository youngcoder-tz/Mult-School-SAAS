<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\ProfileRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\InstructorFeature;
use App\Models\InstructorProcedure;
use App\Models\Organization;
use App\Models\State;
use App\Models\Student;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    use ImageSaveTrait, General, SendNotification, ApiStatusTrait;

    protected $studentModel;
    protected $organizationModel;
    protected $instructorModel;

    public function __construct(Student $student, Instructor $instructor, Organization $organization)
    {
        $this->studentModel = new Crud($student);
        $this->instructorModel = new Crud($instructor);
        $this->organizationModel = new Crud($organization);
    }

    /**
     * Get student profile
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        try {
            $user = Auth::user();
            $user->load('student');
            
            return $this->success([
                'user' => $user,
                'student' => $user->student
            ]);
        } catch (\Exception $e) {
            return $this->failed([], __('Failed to load profile'), 500);
        }
    }

    /**
     * Update student profile
     * 
     * @param ProfileRequest $request
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveProfile(ProfileRequest $request, $uuid)
    {
        try {
            DB::beginTransaction();
            
            $student = $this->studentModel->getRecordByUuid($uuid);
            $user = User::findOrFail($student->user_id);

            // Handle image upload
            $image = $user->image;
            if ($request->hasFile('image')) {
                $this->deleteFile($user->image);
                $image = $this->saveImage('user', $request->file('image'));
            }

            // Update user data
            $user->update([
                'name' => trim("{$request->first_name} {$request->last_name}"),
                'image' => $image,
                'mobile_number' => $request->mobile_number,
                'phone_number' => $request->mobile_number,
                'address' => $request->address,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'og_image' => $request->hasFile('og_image') 
                    ? $this->saveImage('meta', $request->file('og_image')) 
                    : $user->og_image
            ]);

            // Update student data
            $this->studentModel->updateByUuid([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'phone_number' => $request->mobile_number,
                'about_me' => $request->about_me,
                'gender' => $request->gender,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
            ], $uuid);

            DB::commit();

            return $this->success([
                'user' => $user->fresh(),
                'student' => $student->fresh()
            ], __('Profile updated successfully'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failed([], __('Failed to update profile: ') . $e->getMessage(), 500);
        }
    }

    /**
     * Change password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePasswordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'new_password' => 'required|min:8|max:64|confirmed|different:password',
        ], [
            'new_password.different' => __('The new password must be different from current password')
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), __('Validation failed'), 422);
        }

        try {
            $user = Auth::user();
            
            if ($user->email != $request->email) {
                return $this->error([], __('Invalid email'), 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                return $this->error([], __('Current password is incorrect'), 401);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return $this->success([], __('Password changed successfully'));
            
        } catch (\Exception $e) {
            return $this->error([], __('Password change failed: ') . $e->getMessage(), 500);
        }
    }

    /**
     * Get instructor application information
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function becomeAnInstructor()
    {
        try {
            $user = Auth::user();
            
            if ($user->role == USER_ROLE_INSTRUCTOR) {
                return $this->error([], __('You are already an instructor!'), 400);
            }
            
            if ($user->role == USER_ROLE_ORGANIZATION) {
                return $this->error([], __('You are already an organization!'), 400);
            }

            return $this->success([
                'instructor_features' => InstructorFeature::take(3)->get(),
                'instructor_procedures' => InstructorProcedure::all(),
                'stats' => [
                    'total_students' => Student::count(),
                    'total_enrollments' => Enrollment::count(),
                    'total_instructors' => Instructor::count()
                ]
            ]);
            
        } catch (\Exception $e) {
            return $this->error([], __('Failed to load instructor information'), 500);
        }
    }

    /**
     * Submit instructor application
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveInstructorInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'professional_title' => 'required|string|max:255',
            'about_me' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'cv_file' => 'required|file|mimes:pdf|max:5120', // 5MB
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), __('Validation failed'), 422);
        }

        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            
            if (Instructor::where('user_id', $user->id)->exists()) {
                return $this->error([], __('Request already submitted'), 400);
            }

            // Generate unique slug
            $baseSlug = Str::slug($user->name);
            $slug = Instructor::where('slug', $baseSlug)->exists() 
                ? $baseSlug . '-' . Str::random(6)
                : $baseSlug;

            // Handle CV file upload
            $cvFile = $this->uploadFileWithDetails('instructor_cv', $request->file('cv_file'));
            if (!$cvFile['is_uploaded']) {
                throw new \Exception(__('Failed to upload CV file'));
            }

            // Create instructor record
            $instructor = $this->instructorModel->create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'professional_title' => $request->professional_title,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'about_me' => $request->about_me,
                'slug' => $slug,
                'cv_file' => $cvFile['path'],
                'cv_filename' => $cvFile['original_filename'],
                'status' => STATUS_PENDING
            ]);

            // Send notification
            $this->send(__("New Instructor request from {$user->name}"), 1);

            DB::commit();

            return $this->success([
                'instructor' => $instructor
            ], __('Application submitted successfully'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], __('Application failed: ') . $e->getMessage(), 500);
        }
    }

    /**
     * Get states by country
     * 
     * @param int $country_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStateByCountry($country_id)
    {
        try {
            $states = State::where('country_id', $country_id)
                ->orderBy('name')
                ->get(['id', 'name']);
                
            return $this->success([
                'states' => $states
            ]);
            
        } catch (\Exception $e) {
            return $this->error([], __('Failed to load states'), 500);
        }
    }

    /**
     * Get cities by state
     * 
     * @param int $state_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCityByState($state_id)
    {
        try {
            $cities = City::where('state_id', $state_id)
                ->orderBy('name')
                ->get(['id', 'name']);
                
            return $this->success([
                'cities' => $cities
            ]);
            
        } catch (\Exception $e) {
            return $this->error([], __('Failed to load cities'), 500);
        }
    }
}