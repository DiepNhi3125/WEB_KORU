<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Thông tin cá nhân
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Cập nhật họ tên, địa chỉ email và số điện thoại tài khoản của bạn.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Họ và tên -->
        <div>
            <x-input-label for="name" value="Họ và tên" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" placeholder="Nhập họ và tên" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Địa chỉ Email -->
        <div>
            <x-input-label for="email" value="Địa chỉ Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" placeholder="example@koru.vn" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- Số điện thoại -->
        <div>
            <x-input-label for="phone" value="Số điện thoại" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" required placeholder="Nhập số điện thoại" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Nút Lưu -->
        <div class="flex items-center gap-4">
            <x-primary-button>Lưu thay đổi</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-semibold"
                >Đã lưu thành công!</p>
            @endif
        </div>
    </form>
</section>