new DataTable("#myTable", {
    order: [],
    layout: {
        bottomStart: "pageLength",
    },
});

new DataTable("#AcademicAdvancement", {
    order: [],
    layout: {
        bottomStart: "pageLength",
    },
});

new DataTable("#expenseTable", {
    order: [],
    language: {
        searchBuilder: {
            title: {
                0: "Custom Filter",
                _: "Custom Filter (%d)",
            },
        },
    },
    layout: {
        top1: "searchBuilder",
        topStart: {
            buttons: [
                {
                    extend: "print",
                    customize: function (win) {
                        const imgUrl =
                            "http://127.0.0.1:8000/admin_assets/img/2.png";
                        $(win.document.body).append(
                            `
                            <style>
                                .watermark {
                                    position: fixed;
                                    top: 50%;
                                    left: 50%;
                                    transform: translate(-50%, -50%);
                                    opacity: 0.1; (0.1 = very faint, 0.5 = more visible)
                                    z-index: -1;  
                                    pointer-events: none;  
                                }
                            </style>
                        `
                        ).prepend(`
                                <div class="watermark">
                                    <img src="${imgUrl}" style="height: 700px;">
                                </div>
                            `);
                        $(win.document.body)
                            .find("table")
                            .addClass("compact")
                            .css("font-size", "inherit");
                    },
                    messageTop:
                        "Mother Shepherd Academy of Valenzuela: Accounting Management System - Expense Directory",
                },
            ],
        },
        bottomStart: "pageLength",
    },
});

new DataTable("#paymentTable", {
    columnDefs: [{ targets: 7, visible: false }],
    scrollX: true,
    order: [],
    language: {
        searchBuilder: {
            title: {
                0: "Custom Filter",
                _: "Custom Filter (%d)",
            },
        },
    },
    layout: {
        top1: "searchBuilder",
        topStart: {
            buttons: [
                {
                    extend: "print",
                    customize: function (win) {
                        const imgUrl =
                            "http://127.0.0.1:8000/admin_assets/img/2.png";
                        $(win.document.body).append(
                            `
                            <style>
                                .watermark {
                                    position: fixed;
                                    top: 50%;
                                    left: 50%;
                                    transform: translate(-50%, -50%);
                                    opacity: 0.1;  // Adjust transparency (0.1 = very faint, 0.5 = more visible)
                                    z-index: -1;  
                                    pointer-events: none;  
                                }
                            </style>
                        `
                        ).prepend(`
                                <div class="watermark">
                                    <img src="${imgUrl}" style="height: 700px;">
                                </div>
                            `);
                        $(win.document.body)
                            .find("table")
                            .addClass("compact")
                            .css("font-size", "inherit");
                    },
                    messageTop:
                        "Mother Shepherd Academy of Valenzuela: Accounting Management System - Payment Records",
                },
            ],
        },
        bottomStart: "pageLength",
    },
});

new DataTable("#allocateTable", {
    order: [],
    language: {
        searchBuilder: {
            title: {
                0: "Custom Filter",
                _: "Custom Filter (%d)",
            },
        },
    },
    layout: {
        top1: "searchBuilder",
        topStart: {
            buttons: [
                {
                    extend: "print",
                    customize: function (win) {
                        const imgUrl =
                            "http://127.0.0.1:8000/admin_assets/img/2.png";
                        $(win.document.body).append(
                            `
                            <style>
                                .watermark {
                                    position: fixed;
                                    top: 50%;
                                    left: 50%;
                                    transform: translate(-50%, -50%);
                                    opacity: 0.1; (0.1 = very faint, 0.5 = more visible)
                                    z-index: -1;  
                                    pointer-events: none;  
                                }
                            </style>
                        `
                        ).prepend(`
                                <div class="watermark">
                                    <img src="${imgUrl}" style="height: 700px;">
                                </div>
                            `);
                        $(win.document.body)
                            .find("table")
                            .addClass("compact")
                            .css("font-size", "inherit");
                    },
                    messageTop:
                        "Mother Shepherd Academy of Valenzuela: Accounting Management System - Allocate Budget Records",
                },
            ],
        },
        bottomStart: "pageLength",
    },
});

new DataTable("#replenishTable", {
    order: [],
    language: {
        searchBuilder: {
            title: {
                0: "Custom Filter",
                _: "Custom Filter (%d)",
            },
        },
    },
    layout: {
        top1: "searchBuilder",
        topStart: {
            buttons: [
                {
                    extend: "print",
                    customize: function (win) {
                        const imgUrl =
                            "http://127.0.0.1:8000/admin_assets/img/2.png";
                        $(win.document.body).append(
                            `
                            <style>
                                .watermark {
                                    position: fixed;
                                    top: 50%;
                                    left: 50%;
                                    transform: translate(-50%, -50%);
                                    opacity: 0.1; (0.1 = very faint, 0.5 = more visible)
                                    z-index: -1;  
                                    pointer-events: none;  
                                }
                            </style>
                        `
                        ).prepend(`
                                <div class="watermark">
                                    <img src="${imgUrl}" style="height: 700px;">
                                </div>
                            `);
                        $(win.document.body)
                            .find("table")
                            .addClass("compact")
                            .css("font-size", "inherit");
                    },
                    messageTop:
                        "Mother Shepherd Academy of Valenzuela: Accounting Management System - Replenish Expense Records",
                },
            ],
        },
        bottomStart: "pageLength",
    },
});
