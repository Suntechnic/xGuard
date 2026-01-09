<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>xGuard — защита</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: radial-gradient(circle at 20% 20%, #1f4b99, #0d1321 45%), linear-gradient(135deg, #0b1a2b, #0f2c48);
      --card: rgba(255, 255, 255, 0.06);
      --stroke: rgba(255, 255, 255, 0.15);
      --text: #e9f0ff;
      --muted: #9fb5d4;
      --accent: #4fd1c5;
      --accent-2: #8a5cff;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0; min-height: 100vh; font-family: "Space Grotesk", system-ui, sans-serif;
      background: var(--bg); color: var(--text);
      display: flex; justify-content: center; align-items: center; padding: 40px;
    }
    .shell {
      max-width: 1080px; width: 100%;
      backdrop-filter: blur(12px);
      border: 1px solid var(--stroke);
      border-radius: 20px;
      background: linear-gradient(160deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
      box-shadow: 0 30px 80px rgba(0,0,0,0.35);
      padding: 40px 44px;
    }
    .eyebrow {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 8px 14px;
      border-radius: 999px;
      border: 1px solid var(--stroke);
      background: rgba(255,255,255,0.05);
      font-size: 13px; letter-spacing: 0.4px; color: var(--muted);
    }
    .spark { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 0 6px rgba(79,209,197,0.2); }
    h1 {
      margin: 18px 0 12px; font-size: 48px; line-height: 1.05;
      letter-spacing: -0.02em;
      background: linear-gradient(90deg, #b9d8ff, #4fd1c5 40%, #8a5cff 80%);
      -webkit-background-clip: text; color: transparent;
    }
    p.lead { margin: 0 0 28px; max-width: 720px; color: var(--muted); font-size: 18px; line-height: 1.6; }
    .grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 18px; margin-top: 6px;
    }
    .card {
      padding: 18px 18px 16px;
      border-radius: 14px;
      border: 1px solid var(--stroke);
      background: var(--card);
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.03);
    }
    .card h3 { margin: 0 0 8px; font-size: 18px; color: #f4f7ff; }
    .card p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.5; }
    .pill {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 6px 10px; border-radius: 999px;
      background: rgba(79,209,197,0.14); color: #caf7ef; font-size: 12px; letter-spacing: 0.3px;
    }
    /* Auth form */
    .auth {
      margin-top: 32px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 16px;
      align-items: end;
    }
    .auth label { display: block; font-size: 13px; color: var(--muted); margin-bottom: 6px; }
    .auth input {
      width: 100%; padding: 12px 14px;
      border-radius: 10px; border: 1px solid var(--stroke);
      background: rgba(255,255,255,0.05); color: var(--text);
      font-size: 15px;
    }
    .auth input:focus { outline: 1px solid var(--accent); box-shadow: 0 0 0 4px rgba(79,209,197,0.18); }
    .auth button {
      padding: 13px 16px;
      border: none; border-radius: 12px;
      background: linear-gradient(120deg, var(--accent), var(--accent-2));
      color: #0b1321; font-weight: 700; font-size: 15px;
      cursor: pointer; box-shadow: 0 12px 28px rgba(79,209,197,0.25);
      transition: transform 0.1s ease, box-shadow 0.1s ease;
    }
    .auth button:hover { transform: translateY(-1px); box-shadow: 0 16px 32px rgba(79,209,197,0.3); }
    .auth button:active { transform: translateY(0); }
  </style>
</head>
<body>
  <main class="shell">
    <div class="eyebrow"><span class="spark"></span> Active defense layer</div>
    <h1>xGuard</h1>
    <p class="lead">
      Сервис активной защиты: мониторинг, фильтрация трафика, мгновенные оповещения и аналитика
      инцидентов. Прозрачное подключение без простоя.
    </p>

    

