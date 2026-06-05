<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportContactRequest;
use App\Http\Requests\StoreContactRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class ContactController extends Controller
{
    /**
     * お問い合わせ入力画面を表示する
     */
    public function index()
    {
        // お問い合わせ種類の選択肢を取得
        $categories = Category::all();

        // タグの選択肢を取得
        $tags = Tag::all();

        // 入力画面では categories と tags だけ渡す
        return view('contact.index', compact('categories', 'tags'));
    }

    /**
     * お問い合わせ確認画面を表示する
     */
    public function confirm(StoreContactRequest $request)
    {
        // バリデーション済みの入力データを取得
        $validated = $request->validated();

        // confirm.blade.php 側で $contact を使っていても動くように同じ内容を入れる
        $contact = $validated;

        // 確認画面に表示するカテゴリを取得
        $category = Category::findOrFail($validated['category_id']);

        // 確認画面に表示するタグを取得
        $tags = Tag::whereIn('id', $validated['tag_ids'] ?? [])->get();

        // confirm.blade.php 側で $selectedTags を使っていても動くように同じ内容を入れる
        $selectedTags = $tags;

        // 確認画面では入力値・カテゴリ・タグを渡す
        return view('contact.confirm', compact(
            'validated',
            'contact',
            'category',
            'tags',
            'selectedTags'
        ));
    }

    /**
     * お問い合わせ内容を保存する
     */
    public function store(StoreContactRequest $request)
    {
        // バリデーション済みの入力データを取得
        $validated = $request->validated();

        // タグIDを別変数に退避
        $tagIds = $validated['tag_ids'] ?? [];

        // contacts テーブルには tag_ids カラムがないため削除
        unset($validated['tag_ids']);

        // お問い合わせ内容を contacts テーブルに保存
        $contact = Contact::create($validated);

        // タグが選択されている場合、中間テーブル contact_tag に保存
        if (! empty($tagIds)) {
            $contact->tags()->attach($tagIds);
        }

        // 送信完了画面へ遷移
        return redirect('/thanks');
    }

    /**
     * 送信完了画面を表示する
     */
    public function thanks()
    {
        return view('contact.thanks');
    }
    /**
     * お問い合わせ一覧をCSVエクスポートする
     */
    public function export(ExportContactRequest $request)
    {
        $query = Contact::with(['category', 'tags']);

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');

            // 半角スペース・全角スペースを除去した検索用キーワード
            $normalizedKeyword = str_replace([' ', '　'], '', $keyword);

            $query->where(function ($query) use ($keyword, $normalizedKeyword) {
                $query->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhereRaw("CONCAT(first_name, last_name) LIKE ?", ["%{$normalizedKeyword}%"])
                    ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ["%{$normalizedKeyword}%"]);
            });
        }

        if ($request->filled('gender') && $request->input('gender') != 0) {
            $query->where('gender', $request->input('gender'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $contacts = $query->latest()->get();

        $fileName = 'contacts_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($contacts) {
            $handle = fopen('php://output', 'w');

            // Excelで文字化けしにくくするためBOMを付ける
            fwrite($handle, "\xEF\xBB\xBF");

            // CSV見出し
            fputcsv($handle, [
                'ID',
                'お名前',
                '性別',
                'メールアドレス',
                '電話番号',
                '住所',
                '建物名',
                'お問い合わせの種類',
                'タグ',
                'お問い合わせ内容',
                '作成日',
            ]);

            $genderLabels = [
                1 => '男性',
                2 => '女性',
                3 => 'その他',
            ];

            foreach ($contacts as $contact) {
                fputcsv($handle, [
                    $contact->id,
                    $contact->first_name . ' ' . $contact->last_name,
                    $genderLabels[$contact->gender] ?? '',
                    $contact->email,
                    $contact->tel,
                    $contact->address,
                    $contact->building,
                    $contact->category->content ?? '',
                    $contact->tags->pluck('name')->join(', '),
                    $contact->detail,
                    $contact->created_at->format('Y/m/d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
