let BaseDataTable = (function () {
    let initTable = function (options) {
        const optionalId = (options?.id ? options.id : "");

        let table = $("#tableData" + optionalId).DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            scrollX: true,
            serverSide: true,
            pageLength: 50,
            lengthMenu: [
                [50, 80, 100, -1],
                [50, 80, 100, "All"],
            ],
            order: [[0, "desc"]],
            buttons: [
                {
                    extend: "print",
                    exportOptions: {
                        columns: ":visible",
                        columns: ":not(.notexport)",
                    },
                },
                {
                    extend: "copyHtml5",
                    exportOptions: {
                        columns: ":visible",
                        columns: ":not(.notexport)",
                    },
                },
                {
                    extend: "excelHtml5",
                    exportOptions: {
                        columns: ":visible",
                        columns: ":not(.notexport)",
                    },
                },
                {
                    extend: "csvHtml5",
                    exportOptions: {
                        columns: ":visible",
                        columns: ":not(.notexport)",
                    },
                },
                {
                    extend: "pdfHtml5",
                    exportOptions: {
                        columns: ":visible",
                        columns: ":not(.notexport)",
                    },
                },
            ],
            // columnDefs: [
            //     {
            //         targets: -1,
            //         className: "float-end",
            //     },
            // ],
            ...options,
        });

        const exportData = (e, index) => {
            e.preventDefault();
            table.button(index).trigger();
        };

        $("#export_print" + optionalId).on("click", (e) => exportData(e, 0));
        $("#export_copy" + optionalId).on("click", (e) => exportData(e, 1));
        $("#export_excel" + optionalId).on("click", (e) => exportData(e, 2));
        $("#export_csv" + optionalId).on("click", (e) => exportData(e, 3));
        $("#export_pdf" + optionalId).on("click", (e) => exportData(e, 4));

        let delayTimer;

        $("#previewList").on("click", function () {
            table.draw();
        });
        $("#searchDatatable" + optionalId).on("keyup", function () {
            clearTimeout(delayTimer);
            let searchValue = this.value;
            delayTimer = setTimeout(function () {
                table.draw();
            }, 1000);
        });

        $("#filterDatatable" + optionalId).on("click", function () {
            table.draw();
        });
        $("#filterDatatable1" + optionalId).on("click", function () {
            table.draw();
        });
        $("#filterDatatable2" + optionalId).on("click", function () {
            table.draw();
        });
        $("#filterDatatable3" + optionalId).on("click", function () {
            table.draw();
        });
        $("#filterDatatable4" + optionalId).on("click", function () {
            table.draw();
        });
        $("#filterDatatable5" + optionalId).on("click", function () {
            table.draw();
        });

        // $("#resetDatatable").on("click", () => {
        //     $("#status_filter").val(null).trigger("change");
        //     table.draw();
        // });

        $("body").on("click", ".btnDelete ", function (event) {
            console.log("clicked delete btn");
            var url = $(this).data("url");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    let deleted = deleteRow(url);
                    if (deleted) {
                        $("#tableData").DataTable().ajax.reload();
                        Swal.fire(
                            "Deleted!",
                            "Row has been deleted.",
                            "success"
                        );
                    } else {
                        Swal.fire(
                            "Failed!",
                            "Row could not be deleted.",
                            "error"
                        );
                    }
                }
            });
        });
        $("body").on("click", ".changeBtn ", function (event) {
            var url = $(this).data("url");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Change it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    let deleted = deleteRow(url);
                    if (deleted) {
                        $("#tableData").DataTable().ajax.reload();
                        Swal.fire(
                            "Updated!",
                            "Row has been Updated.",
                            "success"
                        );
                    } else {
                        Swal.fire(
                            "Failed!",
                            "Row could not be Updated.",
                            "error"
                        );
                    }
                }
            });
        });

        $("body").on("click", ".btnPost ", function (event) {
            var url = $(this).data("url");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Change it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    let deleted = deleteRow(url);
                    if (deleted) {
                        $("#tableData").DataTable().ajax.reload();
                        Swal.fire(
                            "Updated!",
                            "Event Posted",
                            "success"
                        );
                    } else {
                        Swal.fire(
                            "Failed!",
                            "Event Cannot Posted",
                            "error"
                        );
                    }
                }
            });
        });
        $("body").on("click", ".btnAutomate ", function (event) {
            var url = $(this).data("url");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Add this !",
            }).then((result) => {
                if (result.isConfirmed) {
                    let send = automateSend(url);
                    if (send) {
                        $("#tableData").DataTable().ajax.reload();
                        Swal.fire(
                            "Added!",
                            "Row has been Added.",
                            "success"
                        );
                    } else {
                        Swal.fire(
                            "Failed!",
                            "Row couldn't Added.",
                            "error"
                        );
                    }
                }
            });
        });
        $("body").on("click", ".btnFlushRedis ", function (event) {
            var url = $(this).data("url");
            Swal.fire({
                title: "Are you sure want to Remove ?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Remove!",
            }).then((result) => {
                if (result.isConfirmed) {
                    let send = flushRedish(url);
                    if (send) {
                        window.location.reload();
                        Swal.fire(
                            "Removed!",
                            "Queue Email Removed",
                            "success"
                        );
                    } else {
                        Swal.fire(
                            "Failed!",
                            "Email couldn't sent.",
                            "error"
                        );
                    }
                }
            });
        });
        $("body").on("click", ".btnResendOTP ", function (event) {
            var url = $(this).data("url");
            Swal.fire({
                title: "Are you sure want to Send ?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Sent!",
            }).then((result) => {
                if (result.isConfirmed) {
                    let send = reSendOTP(url);
                    if (send) {
                        $("#tableData").DataTable().ajax.reload();
                        Swal.fire(
                            "Sent!",
                            "Verification Email Sent",
                            "success"
                        );
                    } else {
                        Swal.fire(
                            "Failed!",
                            "Email couldn't sent.",
                            "error"
                        );
                    }
                }
            });
        });
        $("body").on("click", ".btnDisable2fa ", function (event) {
            var url = $(this).data("url");

            Swal.fire({
                title: "Are you sure?",
                text: "You want to Disable Google 2FA!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Disable!",
            }).then((result) => {
                if (result.isConfirmed) {
                    //Make Delete Request via ajax call
                    let switched = disable2fa(url);
                    if (switched) {
                        location.reload();
                        Swal.fire(
                            "Disabled!",
                            "The user has been Disabled Google 2FA.",
                            "success"
                        );

                    } else {
                        Swal.fire(
                            "Failed!",
                            "Something went wrong.",
                            "error"
                        );
                    }
                }
            });
        });
        $("body").on("click", ".btnUnmarkUnsubscribe ", function (event) {
            var url = $(this).data("url");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to Unmark Unsubscribe !!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Unmark!",
            }).then((result) => {
                if (result.isConfirmed) {
                    //Make Delete Request via ajax call
                    let switched = unMarkUnsubscribe(url);
                    if (switched) {
                        location.reload();
                        Swal.fire(
                            "Unmarked!",
                            "The user has been Unmarked from unsubscribe.",
                            "success"
                        );

                    } else {
                        Swal.fire(
                            "Failed!",
                            "Something went wrong.",
                            "error"
                        );
                    }
                }
            });
        });
        $("body").on("click", ".btnSyncReferral ", function (event) {
            var url = $(this).data("url");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to Unmark Unsubscribe !!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Unmark!",
            }).then((result) => {
                if (result.isConfirmed) {
                    //Make Delete Request via ajax call
                    let switched = unMarkUnsubscribe(url);
                    if (switched) {
                        location.reload();
                        Swal.fire(
                            "Unmarked!",
                            "The user has been Unmarked from unsubscribe.",
                            "success"
                        );

                    } else {
                        Swal.fire(
                            "Failed!",
                            "Something went wrong.",
                            "error"
                        );
                    }
                }
            });
        });
        $("body").on("click", ".btnUnsubscribe ", function (event) {
            var url = $(this).data("url");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to Unsubscribe !!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Unsubscribe!",
            }).then((result) => {
                if (result.isConfirmed) {
                    //Make Delete Request via ajax call
                    let switched = unsubscribe(url);
                    if (switched) {
                        location.reload();
                        Swal.fire(
                            "Unsubscribed!",
                            "The user has been Unsubscribed .",
                            "success"
                        );

                    } else {
                        Swal.fire(
                            "Failed!",
                            "Something went wrong.",
                            "error"
                        );
                    }
                }
            });
        });
        $("body").on("click", ".btnSpinnerReset ", function (event) {
            var url = $(this).data("url");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to Reset Spinner !!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Reset!",
            }).then((result) => {
                if (result.isConfirmed) {
                    //Make Delete Request via ajax call
                    let switched = spinnerReset(url);
                    if (switched) {
                        location.reload();
                        Swal.fire(
                            "Reset!",
                            "The Spinner Has been Reset.",
                            "success"
                        );

                    } else {
                        Swal.fire(
                            "Failed!",
                            "Something went wrong.",
                            "error"
                        );
                    }
                }
            });
        });

        $("body").on("click", ".btnSendEmail ", function (event) {
            var url = $(this).data("url");

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Send it!",
            }).then((result) => {
                if (result.isConfirmed) {

                    let send = sendEmail(url);
                    if (send) {
                        $("#tableData").DataTable().ajax.reload();
                        Swal.fire(
                            "Maile sent to queue",
                        );
                    } else {
                        Swal.fire(
                            "Failed!",
                            "Mail could not be Sent.",
                            "error"
                        );
                    }
                }
            });
        });
        $("body").on("click", ".btnSendEmailNotification", function (event) {
            var url = $(this).data("url");

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Send it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    let send = sendEmail(url);
                    if (send) {
                        $("#tableData").DataTable().ajax.reload();
                        Swal.fire(
                            "Mail sent to queue",
                            "You will be redirected to the success page",
                            "success"
                        ).then(() => {
                            // Redirect to success page
                            window.location.href = "success.html";
                        });
                    } else {
                        Swal.fire(
                            "Failed!",
                            "Mail could not be sent.",
                            "error"
                        );
                    }
                }
            });
        });

        $("body").on("click", ".btnConfirmTransaction ", function (event) {
            var url = $(this).data("url");
            Swal.fire({
                title: "Are you sure?",

                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Confirm this Transaction",
            }).then((result) => {
                if (result.isConfirmed) {
                    let switched = confirmTransaction(url);
                    if (switched) {
                        // location.reload();
                        $("#tableData").DataTable().ajax.reload();
                        Swal.fire(
                            "Success",
                            "This Transaction was Successful",
                            "success"
                        );
                        $('#transactionDetailsModal').modal('hide');

                    } else {
                        Swal.fire(
                            "Failed!",
                            "Something went wrong.",
                            "error"
                        );
                    }
                }
            });

        });

        return table;
    };

    function deleteRow(url, id) {
        var result = false;
        $.ajax({
            type: "DELETE",
            url: url,
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                result = data;
            },
            error: function (data) {
                result = false;
            },
        });
        return result;
    }

    function automateSend(url, id) {
        var result = false;
        $.ajax({
            type: "get",
            url: url,
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                result = data;
            },
            error: function (data) {
                result = false;
            },
        });
        return result;
    }

    function disable2fa(url, id) {
        var result = false;
        $.ajax({
            type: "get",
            url: url,
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                result = data;
            },
            error: function (data) {
                result = false;
            },
        });
        return result;
    }


    function unMarkUnsubscribe(url, id) {
        var result = false;
        $.ajax({
            type: "get",
            url: url,
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                result = data;
            },
            error: function (data) {
                result = false;
            },
        });
        return result;
    }

    function reSendOTP(url, id) {
        var result = false;
        $.ajax({
            type: "get",
            url: url,
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                result = data;
            },
            error: function (data) {
                result = false;
            },
        });
        return result;
    }

    function flushRedish(url, id) {
        var result = false;
        $.ajax({
            type: "get",
            url: url,
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                result = data;
            },
            error: function (data) {
                result = false;
            },
        });
        return result;
    }

    function unsubscribe(url, id) {
        var result = false;
        $.ajax({
            type: "get",
            url: url,
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                result = data;
            },
            error: function (data) {
                result = false;
            },
        });
        return result;
    }

    function spinnerReset(url, id) {
        var result = false;
        $.ajax({
            type: "get",
            url: url,
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                result = data;
            },
            error: function (data) {
                result = false;
            },
        });
        return result;
    }

    function confirmTransaction(url, id) {
        var result = false;
        $.ajax({
            type: "get",
            url: url,
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                result = data;
            },
            error: function (data) {
                result = false;
            },
        });
        return result;
    }

    // function ConfirmTransaction(url, id) {
    //     var result = false;
    //     $.ajax({
    //         type: "get",
    //         url: url,
    //         async: false,
    //         beforeSend: function() {},
    //         success: function(data) {
    //             result = data;
    //         },
    //         error: function(data) {
    //             result = false;
    //         },
    //     });
    //     return result;
    // }
    function sendEmail(url, id) {
        var result = false;
        $.ajax({
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                result = data;
            },
            error: function (data) {
                result = false;
            },
        });
        return result;
    }

    return {
        initTable,
    };
})();
