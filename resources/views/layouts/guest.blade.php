<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'KORU' }}</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap');

        :root {
            --primary: #3474f5;
            --primary-dark: #1f5fdd;
            --navy: #0c235a;
            --text: #29416f;
            --muted: #5b7098;
            --border: #e4ebf7;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background: linear-gradient(135deg, #edf5ff, #ffffff);
            color: var(--text);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 40px 16px;
        }

        .auth-wrapper {
            width: min(100%, 440px);
            margin: 0 auto;
        }

        .auth-brand {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 24px;
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
        }

        .auth-card {
            padding: 36px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 12px 40px rgba(12, 35, 90, 0.06);
            text-align: left;
        }

        .auth-heading {
            margin-bottom: 26px;
            text-align: center;
        }

        .auth-heading h1 {
            font-size: 26px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .auth-heading p {
            color: var(--muted);
            font-size: 14px;
            font-weight: 500;
        }

        .auth-form {
            display: grid;
            gap: 18px;
        }

        .auth-field {
            display: grid;
            gap: 7px;
        }

        .auth-field label {
            color: var(--navy);
            font-size: 13px;
            font-weight: 600;
        }

        .auth-field input {
            width: 100%;
            min-height: 45px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 14px;
            background: #ffffff;
            color: var(--navy);
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .auth-field input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 116, 245, 0.15);
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
            border: none;
            font-weight: 700;
            cursor: pointer;
        }

        .auth-submit {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px;
            border-radius: 10px;
            margin-top: 10px;
            font-size: 15px;
            font-family: inherit;
        }

        .auth-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            font-size: 12px;
        }

        .auth-remember {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--muted);
        }

        .auth-options a, .auth-switch a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-switch {
            margin: 22px 0 0;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="auth-wrapper">
        <a href="{{ url('/') }}" class="auth-brand">KORU</a>
        <div class="auth-card">
            {{ $slot }}
        </div>
    </div>

</body>
</html>