<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="author" content="Olivier Roques">
    <meta name="description" content="Home page">
    <title>home</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:500" rel="stylesheet">
    <style>
      :root {
        --font: "Roboto Mono";
        --background: #272822;
        --foreground: #f8f8f2;
        --blue: #66d9ef;
        --green: #a6e22e;
        --red: #f92672;
        --yellow: #f4bf75;
        --branch: 1px solid var(--foreground);
      }

      html {
        font-size: 20px;
      }

      <?php
        $color = "var(--background)";
        if (isset($_GET['c'])) {
          if (ctype_xdigit($_GET['c']) && strlen($_GET['c']) == 6) {
            $color = sprintf("#%s", strtolower($_GET['c']));
          }
        }
        printf("body {background: %s;}", $color);
      ?>

      .container {
        position: absolute;
        top: 50%;
        left: 45%;
        transform: translate(-50%, -50%);
      }

      .prompt {
        font-family: var(--font);
        color: var(--foreground);
      }

      .prompt~.prompt {
        padding: 1.5rem 0 0.3125rem;
      }

      h1 {
        display: inline;
        font-family: var(--font);
        font-size: 1rem;
        font-weight: normal;
        color: var(--blue);
      }

      #search {
        color: var(--red);
      }

      .user {
        color: var(--green);
      }

      .dir {
        color: var(--blue);
      }

      .tree > ul {
        margin: 0;
        padding-left: 1rem;
      }

      ul {
        list-style: none;
        padding-left: 2.5rem;
      }

      li {
        position: relative;
      }

      li::before, li::after {
        content: "";
        position: absolute;
        left: -0.75rem;
      }

      li::before {
        border-top: var(--branch);
        top: 0.75rem;
        width: 0.5rem;
      }

      li::after {
        border-left: var(--branch);
        height: 100%;
        top: 0.25rem;
      }

      li:last-child::after {
        height: 0.5rem;
      }

      a {
        font-family: var(--font);
        font-size: 1rem;
        color: var(--foreground);
        text-decoration: none;
        outline: none;
      }

      a:hover {
        color: var(--background);
        background: var(--yellow);
      }

      form h1 {
        padding-left: 0.125rem;
      }

      input {
        font-family: var(--font);
        font-size: 1rem;
        color: var(--foreground);
        background-color: var(--background);
        border: none;
      }
    </style>
  </head>

  <body>
    <div class="container">

      <div class="prompt"><span class="user">olivier@oroques.dev:</span><span class="dir">~</span>$ tree</div>
        <div class="tree">
        <h1>.</h1>
        <ul>

          <li>
            <h1>news/</h1>
            <ul>
              <li><a href="https://arstechnica.com/">ars technica</a></li>
              <li><a href="https://feedly.com/">feedly</a></li>
              <li><a href="https://news.ycombinator.com/">hacker news</a></li>
              <li><a href="https://www.lemonde.fr/">le monde</a></li>
            </ul>
          </li>

          <li>
            <h1>tools/</h1>
            <ul>
              <li><a href="https://calendar.google.com/">agenda</a></li>
              <li><a href="https://drive.google.com/drive/u/0/">drive</a></li>
              <li><a href="https://github.com/">github</a></li>
              <li><a href="https://www.google.com/gmail">gmail</a></li>
              <li><a href="https://www.messenger.com/">messenger</a></li>
            </ul>
          </li>

          <li>
            <h1>entertainment/</h1>
            <ul>
              <li><a href="https://www.netflix.com/">netflix</a></li>
              <li><a href="https://old.reddit.com/">reddit</a></li>
              <li><a href="https://www.youtube.com/">youtube</a></li>
            </ul>
          </li>

        </ul>
      </div>

      <div class="prompt"><span class="user">olivier@oroques.dev:</span><span class="dir">~</span>$ google</div>
      <form action="https://www.google.com/search" method="GET">
        <h1 id="search">search: </h1>
        <input type="text" name="q" autofocus="autofocus">
      </form>

    </div>
  </body>
</html>
