<?php

namespace App\Http\Controllers\Organization;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index()
    {
        $users = Enrollment::where('enrollments.owner_user_id', auth()->id())
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->join('users', 'enrollments.user_id', '=', 'users.id')
            ->select(
                'courses.id as course_id',
                'courses.title as course_title',
                'users.id as user_id',
                'users.name as user_name',
                'users.image as user_image'
            )->groupBy('users.id', 'courses.id')
            ->get();
        $data['title'] = 'Chat';
        $data['data'] = $users->groupBy('user_id');
        $data['navChatActiveClass'] = "active";
        return view('organization.chat.index', $data);
    }

    public function getChatMessages(Request $request)
    {
        $receiverId = $request->receiverId;
        $senderId = $request->senderId;
        $courseId = $request->courseId;
        $messages = Chat::where(function ($query) use ($senderId, $receiverId, $courseId) {
            $query->where('sender_id', $senderId)
                ->where('receiver_id', $receiverId)
                ->where('course_id', $courseId);
        })
            ->orWhere(function ($query) use ($senderId, $receiverId, $courseId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId)
                    ->where('course_id', $courseId);
            })
            ->orderByDesc('id')
            ->limit(100)
            ->get();
        return response()->json([
            'messages' => $messages
        ]);
    }

    public function sendChatMessage(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'sender_id' => 'required',
                'receiver_id' => 'required',
                'message' => 'required',
                'course_id' => 'required'
            ],
            [
                'sender_id.required' => 'sender is required!',
                'receiver_id.required' => 'receiver is required!',
                'course_id.required' => 'course is required!',
                'message.required' => 'message is required!',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $chat = new Chat();
            $chat->sender_id = $request->sender_id;
            $chat->receiver_id = $request->receiver_id;
            $chat->course_id = $request->course_id;
            $chat->message = $request->message;
            $chat->is_seen = 0;
            $chat->save();

            $messageData = [
                'receiverId' => $request->receiver_id,
                'senderId' => $request->sender_id,
                'courseId' => $request->course_id,
                'message' => $request->message,
            ];
            event(new ChatEvent($messageData));
            return response()->json(['success' => 'message sent successfully!']);
        }
    }
}