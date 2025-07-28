"use strict";
var KTModalUpdateCustomer = (function () {
    var t, e, n, o, c, r;
    return {
        init: function () {
            (t = document.querySelector("#kt_modal_update_customer")),
                (r = new bootstrap.Modal(t)),
                (c = t.querySelector("#kt_modal_update_customer_form")),
                (e = c.querySelector("#kt_modal_update_customer_submit")),
                (n = c.querySelector("#kt_modal_update_customer_cancel")),
                (o = t.querySelector("#kt_modal_update_customer_close")),

                n.addEventListener("click", function (t) {
                    t.preventDefault(),
                        c.reset(),
                        r.hide()
                }),
                o.addEventListener("click", function (t) {
                    t.preventDefault(),
                        c.reset(),
                        r.hide()
                });
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTModalUpdateCustomer.init();
});
