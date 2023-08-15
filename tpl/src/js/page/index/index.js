(function (dep) {
  dep.resolved('$', 'Inputmask', 'EventQueue')
    .then(function ($, Inputmask, EventQueue) {
      EventQueue
        .event("contacts@rendered")
        .subscribe(function () {
          /**
           * Attaches inputmask to all elements which have a data-input attribute
           */
          $("[data-inputmask]").each(function () {
            Inputmask.default().mask(this);
          });
      });
      EventQueue
        .event("getContacts")
        .subscribe(function (uuid) {
          $.ajax(
            "/api/0.0.1/contacts/" + uuid,
            {
              type: 'GET',
              cache: false
            }
          )
            .then(
              function (data) {
                try {
                  if (!$.isArray(data) || typeof data[0] === undefined) {
                    throw new Error("data is corrupted");
                  }
                  if (!!data[0]) {
                    EventQueue.event('failure').publish(data);
                  } else {
                    if (!$.isArray(data[1])) {
                      throw new Error("data is corrupted");
                    }
                    EventQueue.event("contacts@render").publish(data[1]);
                  }
                } catch (err) {
                  EventQueue.event('failure').publish();
                }
              })
            .fail(function (err) {
              EventQueue.event('failure').publish();
            });
        });
      EventQueue
        .event("deleteContact")
        .subscribe(function (uuid, id) {
          $.ajax(
            "/api/0.0.1/contact/" + uuid,
            {
              type: 'DELETE',
              data: "id=" + id
            }
          )
            .then(
              function (data) {
                if (!$.isArray(data) || !!data[0]) {
                  EventQueue.event('failure').publish(new Error("invalid argument"));
                } else {
                  EventQueue.event("reload").publish();
                }
              })
            .fail(function (err) {
              EventQueue.event('failure').publish(err);
            });
        });
      EventQueue
        .event("addContact")
        .subscribe(function (uuid, args) {
          if (typeof args !== "object") {
            EventQueue.event('failure').publish(new Error("invalid argument"));
          }
          $.ajax(
            "/api/0.0.1/contact/" + uuid,
            {
              type: 'POST',
              data: $.param(args)
            }
          )
            .then(
              function (data) {
                try {
                  if (!$.isArray(data) || typeof data[0] === undefined) {
                    throw new Error("data is corrupted");
                  }
                  if (
                    !!data[0] &&
                    (
                      typeof data[1] === "undefined" ||
                      typeof data[1] !== "object" ||
                      typeof data[1].errors !== "object" ||
                      typeof data[1].values !== "object"
                    )
                  ) {
                    throw new Error("data is corrupted");
                  } else {
                    const args = !!data[0]? data[1]: {};
                    if (Object.keys(args).length === 0) {
                      EventQueue.event("reload").publish();
                    } else {
                      EventQueue.event("form@render").publish(args);
                    }
                  }
                } catch (err) {
                  EventQueue.event('failure').publish(err);
                }
              })
            .fail(function (err) {
              EventQueue.event('failure').publish(err);
            });
        });
    });
}) (window.App.dependency);
