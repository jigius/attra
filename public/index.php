<?php

use Local\App;

require_once __DIR__ . "/../vendor/autoload.php";

try {
  $cfg = new App\AppCfg();
  $uuid = (new App\UserAuthCookieDumb("usr", $cfg->fetch("userAuth.ttl", 0)))->uuid();
  ob_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Контакты пользователя (<?= $uuid ?>)</title>
  <link href="/build/images/favicon-16x16.png" rel="icon" />
  <link href="/build/main.css?v_<?= (new App\FileContent(__DIR__ . "/build/main.css"))->hash() ?>" rel="stylesheet">
</head>
<body>
<main>
    <section>
        <div class="container">
            <div class="content d-flex align-items-center justify-content-center">
                <div class="d-flex flex-column">
                    <div class="form-box"></div>
                    <div class="contact-box"></div>
                </div>
            </div>
        </div>
    </section>
</main>
<script type="template/x-html" id="contacts">
  <% if (items.length > 0) { %>
  <div class="contact-box_title">Список контактов</div>
  <% for (let i = 0; i < items.length; i++) { %>
  <hr>
  <div class="contact-box_items">
    <div class="contact-box_item">
      <div class="contact-box_item-info d-flex align-items-center mb-1">
        <%= items[i].name %>
        <div class="contact-box_item-delete js-delete" data-id="<%= items[i].id %>">✕</div>
      </div>
      <?
         $phoneMask = $cfg->fetch("ui.phoneMask", "+9 (999) 999 99-99");
      ?>
      <div class="contact-box_item-tel" data-inputmask="'mask': '<?= $phoneMask?>'"><%= items[i].phone %></div>
    </div>
  </div>
  <% } %>
  <% } %>
</script>
<?php
    $paramName = App\Api\ContactAdd\EndpointInterface::BODY_PARAM_NAME;
    $paramPhone = App\Api\ContactAdd\EndpointInterface::BODY_PARAM_PHONE;
?>
<script type="template/x-html" id="error">
  <div class="alert alert-danger" role="alert"><%= text %></div>
</script>
<script type="template/x-html" id="form">
  <form class="mb-4" autocomplete="off">
    <div class="title-form">Добавить контакт</div>
    <hr>
      <div class="field-wrapper">
        <div class="mb-2">
          <input type="text" class="form-control<%= errors['<?= $paramName ?>'] ? ' is-invalid' : '' %><%= (!errors['<?= $paramName ?>'] && values['<?= $paramName ?>']) ? ' is-valid' : '' %>"
                 id="inputName" placeholder="Имя"
                 name="<?= $paramName ?>"
                 value="<%= values['<?= $paramName ?>'] || "" %>"
          >
          <% if (errors['<?= $paramName ?>']) { %>
          <div class="invalid-feedback"><%= errors['<?= $paramName ?>'] %></div>
          <% } %>
        </div>
        <?
          $phoneMask = $cfg->fetch("ui.phoneMask", "+9 (999) 999 99-99");
        ?>
        <div class="mb-2">
          <input type="tel" class="form-control<%= errors['<?= $paramPhone ?>'] ? ' is-invalid' : '' %><%= (!errors['<?= $paramPhone ?>'] && values['<?= $paramPhone ?>']) ? ' is-valid' : '' %>" id="inputPhone" placeholder="Телефон"
                 name="<?= $paramPhone?>"
                 data-inputmask="'mask': '<?= $phoneMask ?>'"
                 value="<%= values['<?= $paramPhone ?>'] || "" %>"
          >
          <% if (errors['<?= $paramPhone ?>']) { %>
          <div class="invalid-feedback"><%= errors['<?= $paramPhone ?>'] %></div>
          <% } %>
        </div>
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-submit js-add">Добавить</button>
        </div>
      </div>
  </form>
</script>

<script src="/build/main-bundle.js?v=<?= (new App\FileContent(__DIR__ . "/build/main-bundle.js"))->hash() ?>"></script>
<script>
  (function (dep) {
    dep.resolved('$', 'template', 'EventQueue')
      .then(function ($, template, eq) {
        eq
          .event("failure")
          .subscribe(function () {
            $(".content")
              .html(
                template.template(
                  "error",
                  {
                    text: "К сожалению, что-то пошло не так :( Перезагрузите страницу или повторите Ваш запрос позже..."
                  }
                )
              );
          });
        eq
          .event("contacts@render")
          .subscribe(function (contacts) {
            $(".contact-box").html(template.template("contacts", {"items": contacts}));
            eq.event("contacts@rendered").publish();
          });
        eq
          .event("form@render")
          .subscribe(function (args) {
            if (typeof args === "undefined") {
              args = [];
            }
            if (typeof args !== "object") {
              eq.event("failure").publish();
            } else {
              $(".form-box").html(template.template("form", $.extend({"values": {}, "errors": {}}, args)));
              eq.event("contacts@rendered").publish();
            }
          });
        eq
          .event("reload")
          .subscribe(function () {
            eq.event("form@render").publish();
            eq.event("getContacts").publish("<?= $uuid ?>")
          });
        eq.event("reload").publish();
        $(".contact-box").on("click", ".js-delete", function () {
          eq.event("deleteContact").publish("<?= $uuid ?>", $(this).data("id"));
        });
        (function (target, eq, fName, fPhone) {
          target.on("submit", "form", function (event) {
            event.preventDefault();
          });
          target.on("input", ".form-control", function () {
            $(this).removeClass("is-valid");
            $(this).removeClass("is-invalid");
          });
          target.on("click", ".js-add", function () {
            const args = {};
            args[fName] = $("form input[name='" + fName + "']").val() || "";
            args[fPhone] = $("form input[name='" + fPhone + "']").val() || "";
            eq.event("addContact").publish("<?= $uuid ?>", args);
          });
        })($(".form-box"), eq, "<?= $paramName ?>", "<?= $paramPhone ?>");
      });
  }) (window.App.dependency);
</script>
</body>
</html>
<?php
    $retCode = 200;
} catch (Throwable $ex) {
  ob_get_clean();
  echo "error occurred :(";
    $retCode = 500;
}
http_response_code($retCode);
ob_end_flush();
