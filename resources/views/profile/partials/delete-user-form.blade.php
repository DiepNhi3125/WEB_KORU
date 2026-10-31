<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-red-600">
            Xóa tài khoản
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Khi tài khoản bị xóa, toàn bộ dữ liệu liên quan sẽ bị xóa vĩnh viễn. Vui lòng cân nhắc kỹ trước khi thực hiện.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Xóa tài khoản cá nhân</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                Bạn có chắc chắn muốn xóa tài khoản này không?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Nhập mật khẩu của bạn để xác nhận xóa tài khoản vĩnh viễn.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Mật khẩu" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Mật khẩu xác nhận"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Hủy bỏ
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Xác nhận xóa vĩnh viễn
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>