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
        .auth-logo {
    display: block;
    width: 230px;
    max-width: 100%;
    height: auto;
}       /* Thông báo xác thực email thành công */
.auth-success {
    padding: 12px 16px;
    margin-bottom: 20px;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    border-radius: 10px;
    color: #047857;
    font-size: 13px;
    text-align: center;
}

/* Hướng dẫn xác thực email */
.auth-help {
    margin-bottom: 22px;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.7;
    text-align: center;
}

/* Khu vực đăng xuất */
.auth-logout {
    margin-top: 18px;
    text-align: center;
}

/* Nút đăng xuất */
.auth-logout-btn {
    border: none;
    background: transparent;
    color: var(--primary);
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
}

.auth-logout-btn:hover {
    text-decoration: underline;
}

/* KORU - Trang xác thực Email */
.verify-page {
    text-align: center;
}

.verify-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 18px;
    background: #edf4ff;
    color: #3474f5;
}

.verify-page .auth-heading {
    margin-bottom: 20px;
}

.verify-page .auth-heading h1 {
    font-size: 24px;
    letter-spacing: -0.6px;
    color: #0c235a;
}

.verify-email-address {
    padding: 13px 16px;
    border-radius: 10px;
    background: #f4f7fc;
    border: 1px solid #cbd5e1;
    color: #0c235a;
    font-size: 14px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.verify-description {
    margin: 18px 0 20px;
    color: #5b7098;
    font-size: 13px;
    line-height: 1.8;
}

.verify-success {
    margin-bottom: 18px;
    padding: 12px 14px;
    border: 1px solid #a7e6c4;
    border-radius: 10px;
    background: #effaf3;
    color: #146c43;
    font-size: 13px;
    line-height: 1.6;
}

.verify-page .auth-submit {
    min-height: 46px;
    margin-top: 0;
    transition: background 0.2s ease;
}

.verify-page .auth-submit:hover {
    background: #1d5bd8;
}

.verify-help {
    margin-top: 15px;
    color: #5b7098;
    font-size: 12px;
    line-height: 1.7;
}

.verify-footer {
    border-top: 1px solid #cbd5e1;
    margin-top: 24px;
    padding-top: 20px;
}

.verify-logout {
    background: none;
    border: 0;
    color: #3474f5;
    font: inherit;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.verify-logout:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>

    <div class="auth-wrapper">
    <a href="{{ url('/') }}" class="auth-brand" aria-label="KORU - Trang chủ">
        <x-application-logo class="auth-logo" />
    </a>

    <div class="auth-card">
        {{ $slot }}
    </div>
</div>
</body>
</html>