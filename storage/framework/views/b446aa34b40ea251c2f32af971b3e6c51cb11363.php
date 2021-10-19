<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>KSA</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        
    </head>
    <body>
        <div class="">
            
            <div class="d-flex justify-content-end">
                <?php if(Route::has('login')): ?>
                    <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(url('/dashboard')); ?>" class="btn btn-primary">Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Login</a>
                    <?php if(Route::has('register')): ?>
                        <a class="btn btn-danger" href="<?php echo e(route('register')); ?>">Register</a>
                    <?php endif; ?>
    
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="center-items mt-5">
                <img src="/images/logo.png" alt="" style="width: 100%; height: 100%;">
                <h1 style="color: white; margin-top: -50px">The Best Service For Our Customer</h1>
            </div>
        </div>
    </body>
</html>

<style>
    body{
        background-image: url('/images/background-img-2.jpg');
        background-repeat: no-repeat;
        background-size: cover;
    }
    a{
        padding: 10px;
        border-radius: 10%;
        margin-right: 20px;
        margin-top: 40px
    }
    .center-items{
        margin: auto;
        width: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
</style><?php /**PATH D:\Kuliah\Magang\Project\app\app-ver1\resources\views/welcome.blade.php ENDPATH**/ ?>