<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Register</title>
</head>

<body>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <title>Register</title>
    </head>

    <body>
        <!--
  This example requires updating your template:

  ```
  <html class="h-full bg-white">
  <body class="h-full">
  ```
-->
        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-10 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
                <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign up to your account</h2>
            </div>
            
            <?php session_start(); ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    <?= $_SESSION['success']; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-6" action="check_register.php" method="POST">
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="name" class="block text-sm/6 font-medium text-gray-900">Name</label>
                        </div>
                        <div class="mt-2">
                            <input type="name" name="name" id="name" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>

                    <div>
                        <label for="username" class="block text-sm/6 font-medium text-gray-900">Username</label>
                        <div class="mt-2">
                            <input type="username" name="username" id="username" autocomplete="username" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                        </div>
                        <div class="mt-2">
                            <input type="password" name="password" id="password" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="confirm_password" class="block text-sm/6 font-medium text-gray-900">Confirm Password</label>
                        </div>
                        <div class="mt-2">
                            <input type="password" name="confirm_password" id="confirm_password" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="email" class="block text-sm/6 font-medium text-gray-900">Email</label>
                        </div>
                        <div class="mt-2">
                            <input type="email" name="email" id="email" autocomplete="email" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign up</button>
                    </div>
                </form>

                <p class="mt-10 text-center text-sm/6 text-gray-500">
                    a member?
                    <a href="http://localhost/casestudy/login.php" class="font-semibold text-indigo-600 hover:text-indigo-500">Login now</a>
                </p>
            </div>
        </div>
    </body>

    </html>
</body>

</html>