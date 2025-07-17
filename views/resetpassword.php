
<?php $this->title = "Login"; ?>
<main class="flex flex-col justify-center items-center h-full w-full">
    <div class="absolute top-5 left-5">
        <a href="/" class="text-indigo-800 text-sm font-medium flex items-center">
            ⬅️ Home page
        </a>
    </div>
    <div class="bg-white shadow-lg rounded-2xl p-8 max-w-sm w-full">
        <div class="text-center mb-6">
            <h2 class="text-gray-500 text-xl font-semibold">Reset Password</h2>
        </div>
        <form id="resetFrm" class="login-form">
            <input type="text" name="token" id="token" value="<?=$token ?>" >
            <div class="mb-4">
                <label class="text-gray-500 text-sm" for="email">Password</label>
                <input type="password" id="password" name="password" class="w-full mt-1 px-4 py-2 rounded-lg bg-white/20 text-black placeholder:text-gray-200 outline-indigo-800 outline-2 focus:ring-2 focus:ring-blue-300" placeholder="info@gmail.com">
            </div>
            <div class="mb-4">
                <label class="text-gray-500 text-sm" for="email">Confirm Password</label>
                <input type="password" id="confirm_password" name="password" class="w-full mt-1 px-4 py-2 rounded-lg bg-white/20 text-black placeholder:text-gray-200 outline-indigo-800 outline-2 focus:ring-2 focus:ring-blue-300" placeholder="info@gmail.com">
            </div>
            <button id="loginBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg">Update Password</button>
        </form>
        <a href="/login"><button id="login_now_div" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg hidden">Login</button></a>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/scripts/base/base.js"></script>
<script src="/scripts/auth/reset_password.js"></script>