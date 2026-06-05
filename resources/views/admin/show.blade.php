<x-app-layout>
    <x-slot name="header">
        <!-- ログアウトボタン -->
        <form action="/logout" method="post">
            @csrf
            <button
                class="px-5 py-1.5 border border-[#ddd8d3] text-[#c4bab0] bg-white rounded hover:bg-gray-50 transition lowercase text-sm">
                logout
            </button>
        </form>
    </x-slot>

    <div class="min-h-screen bg-white py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-center text-2xl font-serif text-amber-900 mb-6">
                お問い合わせ詳細
            </h2>

            @php
                $genderLabels = [
                    1 => '男性',
                    2 => '女性',
                    3 => 'その他',
                ];
            @endphp

            <!-- 詳細表示 -->
            <div class="border border-gray-200 rounded overflow-hidden">
                <!-- お名前 -->
                <div class="grid grid-cols-3 border-b border-gray-200">
                    <div class="bg-[#baa999] px-6 py-4 flex items-center">
                        <span class="text-sm font-medium text-white">お名前</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                        <span class="text-[#6b5744]">
                            {{ $contact->first_name }} {{ $contact->last_name }}
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
                            {{ $genderLabels[$contact->gender] ?? '' }}
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
                            {{ $contact->email }}
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
                            {{ $contact->tel }}
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
                            {{ $contact->address }}
                        </span>
                    </div>
                </div>

                <!-- 建物名 -->
                @if ($contact->building)
                    <div class="grid grid-cols-3 border-b border-gray-200">
                        <div class="bg-[#baa999] px-6 py-4 flex items-center">
                            <span class="text-sm font-medium text-white">建物名</span>
                        </div>
                        <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                            <span class="text-[#6b5744]">
                                {{ $contact->building }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- お問い合わせの種類 -->
                <div class="grid grid-cols-3 border-b border-gray-200">
                    <div class="bg-[#baa999] px-6 py-4 flex items-center">
                        <span class="text-sm font-medium text-white">お問い合わせの種類</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                        <span class="text-[#6b5744]">
                            {{ $contact->category->content ?? '' }}
                        </span>
                    </div>
                </div>

                <!-- タグ -->
                @if (method_exists($contact, 'tags') && $contact->tags->isNotEmpty())
                    <div class="grid grid-cols-3 border-b border-gray-200">
                        <div class="bg-[#baa999] px-6 py-4 flex items-center">
                            <span class="text-sm font-medium text-white">タグ</span>
                        </div>
                        <div class="col-span-2 bg-white px-6 py-4 flex items-center">
                            <span class="text-[#6b5744]">
                                {{ $contact->tags->pluck('name')->join(', ') }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- お問い合わせ内容 -->
                <div class="grid grid-cols-3">
                    <div class="bg-[#baa999] px-6 py-4 flex items-start">
                        <span class="text-sm font-medium text-white">お問い合わせ内容</span>
                    </div>
                    <div class="col-span-2 bg-white px-6 py-4 flex items-start">
                        <span class="text-[#6b5744] whitespace-pre-wrap">
                            {{ $contact->detail }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- アクションボタン -->
            <div class="flex justify-center gap-4 mt-8">
                <!-- 一覧へ戻るボタン -->
                <a href="/admin"
                    class="px-8 py-3 bg-[#7d7470] hover:bg-[#6b5f57] border border-transparent rounded font-medium text-white transition">
                    一覧に戻る
                </a>

                <!-- 削除フォーム -->
                <!-- 実際の削除処理はこのフォームでPOST + DELETE送信する -->
                <form
                    id="delete-contact-form"
                    action="/admin/contacts/{{ $contact->id }}"
                    method="post"
                >
                    @csrf
                    @method('DELETE')

                    <!-- モーダルを開くボタン -->
                    <!-- type="button" にすることで、押しただけでは削除されないようにする -->
                    <button
                        type="button"
                        onclick="openDeleteModal()"
                        class="px-8 py-3 bg-red-500 hover:bg-red-600 border border-transparent rounded font-medium text-white transition"
                    >
                        削除
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 削除確認モーダル -->
    <!-- 削除ボタンを押したときに表示される確認画面 -->
    <div
        id="delete-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40"
    >
        <div class="w-full max-w-md mx-4 bg-white rounded shadow-lg border border-[#ddd8d3]">
            <!-- モーダル見出し -->
            <div class="px-6 py-4 border-b border-[#f1e8df]">
                <h3 class="text-lg font-serif text-[#6b5744]">
                    削除確認
                </h3>
            </div>

            <!-- モーダル本文 -->
            <div class="px-6 py-6">
                <p class="text-[#6b5744]">
                    このお問い合わせを本当に削除しますか？
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    削除したデータは元に戻せません。
                </p>
            </div>

            <!-- モーダルボタン -->
            <div class="flex justify-end gap-3 px-6 py-4 bg-[#f9f6f2] rounded-b">
                <!-- いいえ：モーダルを閉じるだけで削除しない -->
                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="px-6 py-2 border border-[#ddd8d3] text-[#6b5744] bg-white rounded hover:bg-gray-50 transition"
                >
                    いいえ
                </button>

                <!-- はい：削除フォームを送信する -->
                <button
                    type="button"
                    onclick="submitDeleteForm()"
                    class="px-6 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
                >
                    はい、削除します
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // 削除確認モーダルを表示する
            function openDeleteModal() {
                const modal = document.getElementById('delete-modal');

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            // 削除確認モーダルを閉じる
            function closeDeleteModal() {
                const modal = document.getElementById('delete-modal');

                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            // 削除フォームを送信する
            function submitDeleteForm() {
                document.getElementById('delete-contact-form').submit();
            }
        </script>
    @endpush
</x-app-layout>