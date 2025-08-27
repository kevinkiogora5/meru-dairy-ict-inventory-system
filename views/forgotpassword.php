
<?php $this->title = "Request Password Reset"; ?>
<main class="flex flex-col justify-center items-center h-full w-full">
    <div class="absolute top-5 left-5">
        <a href="/" class="text-indigo-800 text-sm font-medium flex items-center">
            ⬅️ Home page
        </a>
    </div>
    <div class="bg-white shadow-lg rounded-2xl p-8 max-w-sm w-full">
        <div class="text-center mb-6">
            <h2 class="text-gray-500 text-xl font-semibold">Request Reset Password</h2>
        </div>
        <form id="requestPassword" class="login-form">
            <div class="mb-4">
                <label class="text-gray-500 text-sm" for="email">Email</label>
                <input type="email" id="email" name="email" class="w-full mt-1 px-4 py-2 rounded-lg bg-white/20 text-black placeholder:text-gray-200 outline-indigo-800 outline-2 focus:ring-2 focus:ring-blue-300" placeholder="info@gmail.com">
            </div>
            <button id="resetBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg">Request Password Change</button>
            
        </form>
        <a href="/login"><button id="loginBtn"  class="w-full bg-blue-900 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg hidden">Log In</button></a>
    </div>
</main>
<script src="/scripts/openjs/openjs.js"></script>
<script src="/scripts/base/base.js"></script>
<script src="/scripts/auth/request_reset_password.js"></script>