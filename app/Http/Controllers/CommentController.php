<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\User;
use App\Models\Item;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $request->validate([
            'content' => 'required|max:255',
        ]);

        $comment = new Comment();
        $comment->content = $request->input('content');
        $comment->user_id = auth()->id();
        $comment->item_id = $item_id;
        $comment->save();

        return redirect()->route('items.show', ['id' => $item_id])->with('success', 'コメントが投稿されました。');
    }
}
