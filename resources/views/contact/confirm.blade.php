<x-app-layout>
    <!-- お問い合わせ内容確認画面 -->
    <div class="bg-white min-h-screen">
        <div class="max-w-3xl mx-auto px-8 py-12">
            <h1 class="text-2xl font-serif text-[#6b5744] text-center mb-10">
                Confirm
            </h1>

            <!-- 
                送信フォーム
                ここは確認画面ではなく、保存処理用の /contacts にPOSTする
            -->
            <form action="/contacts" method="POST">
                @csrf

                <!-- お名前 -->
                <div class="grid grid-cols-3 border-b border-gray-200">
                    <div class="bg-[#baa999] px-6 py-4 flex items-center">
                        <span class="text-sm font-medium text-white">お名前</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                        <span class="text-[#6b5744]">
                            {{ $contact['first_name'] }} {{ $contact['last_name'] }}
                        </span>
                    </div>
                </div>

                <!-- 性別 -->
                <div class="grid grid-cols-3 border-b border-gray-200">
                    <div class="bg-[#baa999] px-6 py-4 flex items-center">
                        <span class="text-sm font-medium text-white">性別</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                        <span class="text-[#6b5744]">
                            @php
                                $genderLabels = [
                                    1 => '男性',
                                    2 => '女性',
                                    3 => 'その他',
                                ];
                            @endphp

                            {{ $genderLabels[$contact['gender']] ?? '' }}
                        </span>
                    </div>
                </div>

                <!-- メールアドレス -->
                <div class="grid grid-cols-3 border-b border-gray-200">
                    <div class="bg-[#baa999] px-6 py-4 flex items-center">
                        <span class="text-sm font-medium text-white">メールアドレス</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                        <span class="text-[#6b5744]">
                            {{ $contact['email'] }}
                        </span>
                    </div>
                </div>

                <!-- 電話番号 -->
                <div class="grid grid-cols-3 border-b border-gray-200">
                    <div class="bg-[#baa999] px-6 py-4 flex items-center">
                        <span class="text-sm font-medium text-white">電話番号</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                        <span class="text-[#6b5744]">
                            {{ $contact['tel'] }}
                        </span>
                    </div>
                </div>

                <!-- 住所 -->
                <div class="grid grid-cols-3 border-b border-gray-200">
                    <div class="bg-[#baa999] px-6 py-4 flex items-center">
                        <span class="text-sm font-medium text-white">住所</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                        <span class="text-[#6b5744]">
                            {{ $contact['address'] }}
                        </span>
                    </div>
                </div>

                <!-- 建物名 -->
                <div class="grid grid-cols-3 border-b border-gray-200">
                    <div class="bg-[#baa999] px-6 py-4 flex items-center">
                        <span class="text-sm font-medium text-white">建物名</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                        <span class="text-[#6b5744]">
                            {{ $contact['building'] ?? '' }}
                        </span>
                    </div>
                </div>

                <!-- お問い合わせの種類 -->
                <div class="grid grid-cols-3 border-b border-gray-200">
                    <div class="bg-[#baa999] px-6 py-4 flex items-center">
                        <span class="text-sm font-medium text-white">お問い合わせの種類</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                        <span class="text-[#6b5744]">
                            {{ $category->content ?? '' }}
                        </span>
                    </div>
                </div>

                <!-- タグ -->
                <div class="grid grid-cols-3 border-b border-gray-200">
                    <div class="bg-[#baa999] px-6 py-4 flex items-center">
                        <span class="text-sm font-medium text-white">タグ</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                        <span class="text-[#6b5744]">
                            @if ($selectedTags->isNotEmpty())
                                {{ $selectedTags->pluck('name')->join(', ') }}
                            @else
                                未選択
                            @endif
                        </span>
                    </div>
                </div>

                <!-- お問い合わせ内容 -->
                <div class="grid grid-cols-3">
                    <div class="bg-[#baa999] px-6 py-4 flex items-start">
                        <span class="text-sm font-medium text-white">お問い合わせ内容</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-start">
                        <span class="text-[#6b5744] whitespace-pre-wrap">
                            {{ $contact['detail'] }}
                        </span>
                    </div>
                </div>

                <!-- 
                    hidden input
                    確認画面で表示した内容を、保存処理へ送るために保持する
                -->
                <input type="hidden" name="first_name" value="{{ $contact['first_name'] }}">
                <input type="hidden" name="last_name" value="{{ $contact['last_name'] }}">
                <input type="hidden" name="gender" value="{{ $contact['gender'] }}">
                <input type="hidden" name="email" value="{{ $contact['email'] }}">
                <input type="hidden" name="tel" value="{{ $contact['tel'] }}">
                <input type="hidden" name="address" value="{{ $contact['address'] }}">
                <input type="hidden" name="building" value="{{ $contact['building'] ?? '' }}">
                <input type="hidden" name="category_id" value="{{ $contact['category_id'] }}">
                <input type="hidden" name="detail" value="{{ $contact['detail'] }}">

                <!-- 
                    選択されたタグを保存処理へ送る
                    tag_ids[] は複数選択用
                -->
                @if (!empty($contact['tag_ids']))
                    @foreach ($contact['tag_ids'] as $tagId)
                        <input type="hidden" name="tag_ids[]" value="{{ $tagId }}">
                    @endforeach
                @endif

                <!-- ボタン -->
                <div class="flex justify-center gap-4 mt-10">
                    <!-- 送信ボタン：contactsテーブルへ保存する -->
                    <button type="submit"
                        class="px-16 py-3 bg-[#7d7470] hover:bg-[#6b5f57] border border-transparent rounded font-medium text-white transition">
                        送信
                    </button>

                    <!-- 修正ボタン：ブラウザ履歴で入力画面へ戻る -->
                    <button type="button" onclick="history.back()"
                        class="px-8 py-3 text-[#6b5744] transition">
                        修正
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>