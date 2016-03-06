<?php
namespace App\Http\Controllers;

use Auth;
use \App\BoardCard;
use \App\BoardList;
use App\Board;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BoardController extends Controller
{
    public function postBoard(Request $request)
    {
        $this->validate($request, [
            'boardTitle'        => 'required|unique:board,boardTitle',
            'boardPrivacyType'  => 'required',   
        ]);
        
        $boardPrivacyType = $request->get('boardPrivacyType');  
        $boardTitle = $request->get('boardTitle');  
        $userId = Auth::id();
        
        Board::create([
            'user_id' => $userId,
            'boardTitle' => $boardTitle,
            'boardPrivacyType' => $boardPrivacyType,  
        ]);

        return Board::all();
    }

    public function getBoardDetail(Request $request)
    {
       $boardId = $request->id;
       $boardDetail = Board::findOrFail(['id' => $boardId])->first();
       $lists = BoardList::where(["board_id" => $boardId,])->get();
       return view('user.board', compact('boardDetail', 'lists'));
    }

    public function postListName(Request $request)
    {
        $this->validate($request, [
            'list_name' => 'required|unique:board_lists,list_name',
        ]);

        $listName = $request->get('list_name');
        $boardId = $request->get('board_id');
        $userId = Auth::id();
        
        return BoardList::create([
            'board_id' => $boardId,
            'list_name' => $listName,
            'user_id' => $userId,
        ]);
    }  

    public function postCard(Request $request)
    {
        $this->validate($request, [
            'card-title' => 'required|unique:board_cards,card_title',
        ]);

        $cardTitle = $request->get('card-title');
        $listId = $request->get('list_id');
        $boardId = $request->get('board_id');
        $userId = Auth::id();

        return BoardCard::create([
            'board_id' => $boardId,
            'user_id' => $userId,
            'list_id' => $listId,
            'card_title' => $cardTitle,  
        ]);
    }
}
