<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>

{!! Form::open()->id('link-form')->delete() !!}
{!! Form::close() !!}

<script>
    $(function (e) {
        window.submitLinkForm = function submitLinkForm(link)
        {
            event.preventDefault();
            var form = $("form[id=link-form]");
            var msg = typeof $(link).attr('confirm-message') !== typeof undefined ? $(link).attr('confirm-message') : "Supprimer l'enregistrement ?";
            var hasConfirm = $(link).hasClass('confirm') && confirm(msg);

            if($(link).hasClass('confirm') && !hasConfirm)
            {
                return;
            }

            form.attr('action', $(link).attr('form-action'));
            form.find("input[name=_method]").attr('value', $(link).attr('form-method'));
            form.submit();
        };
    })
</script>

<script>
    $(function () {
        $("table > tbody > tr").click(function () {
            $('input:not([disabled]):first', this).focus();
        });
    })
</script>

<script>
    $(function (e) {
        window.filterTable = function(input, table)
        {
            if(table != undefined)
            {
                $("#" + table).DataTable().search($(input).val()).draw();
            }
            else
            {
                $("#datatable").DataTable().search($(input).val()).draw();
            }
        };
    })
</script>

<script>
    $(document).ready(function () {

        window.table = $('#datatable').DataTable({
            responsive: true,
            "bFilter": false, // show search input
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": true,
            "language": {
                "lengthMenu": "Afficher _MENU_ liste de contacts par page",
                "zeroRecords": "Aucun enregistrement",
                "infoEmpty": "No records available",
                "search":         "",
                "paginate": {
                    "first":      "Premier",
                    "last":       "Dernier",
                    "next":       "Suivant",
                    "previous":   "Précédent"
                },
            },
        });

        $("#datatable_filter").css("display", "none !important"); // hidden search input
    });
</script>

<script>
    $(document).ready(function () {

        window.datatableUnordered = $('#datatableUnordered').DataTable({
            responsive: true,
            "bFilter": false, // show search input
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": true,
            "language": {
                "lengthMenu": "Afficher _MENU_ liste de contacts par page",
                "zeroRecords": "Aucun enregistrement",
                "infoEmpty": "No records available",
                "search":         "",
                "paginate": {
                    "first":      "Premier",
                    "last":       "Dernier",
                    "next":       "Suivant",
                    "previous":   "Précédent"
                },
            },
        });

        $("#datatableUnordered_filter").css("display", "none"); // hidden search input
    });
</script>

<script>
    $(document).ready(function () {

        window.table = $('#checkboxtable').DataTable({
            responsive: true,
            "bFilter": false, // show search input
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true,
            "language": {
                "lengthMenu": "Afficher _MENU_ liste de contacts par page",
                "zeroRecords": "Aucun enregistrement",
                "infoEmpty": "No records available",
                "search":         "",
                "paginate": {
                    "first":      "Premier",
                    "last":       "Dernier",
                    "next":       "Suivant",
                    "previous":   "Précédent"
                },
            },

            'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {'selectRow': true},
                }
            ],
            'select': {'style': 'multi'},
            'order': [[1, 'asc']]
        });

        $("#checkboxtable_filter").css("display", "none"); // hidden search input
    });
</script>

<script>
    $(document).ready(function () {

        window.checkboxtableUnordered = $('#checkboxtableUnordered').DataTable({
            responsive: true,
            "bFilter": false, // show search input
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": true,
            "language": {
                "lengthMenu": "Afficher _MENU_ liste de contacts par page",
                "zeroRecords": "Aucun enregistrement",
                "infoEmpty": "No records available",
                "search":         "",
                "paginate": {
                    "first":      "Premier",
                    "last":       "Dernier",
                    "next":       "Suivant",
                    "previous":   "Précédent"
                },
            },

            'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {'selectRow': true},
                }
            ],
            'select': {'style': 'multi'},
            'order': [[1, 'asc']]
        });

        $("#checkboxtableUnordered_filter").css("display", "none"); // hidden search input

    });
</script>

<script>
    $(document).ready(function () {

        window.checkboxtableStyleSingleUnordered = $('#checkboxtableStyleSingleUnordered').DataTable({
            responsive: true,
            "bFilter": false, // show search input
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": true,
            "language": {
                "lengthMenu": "Afficher _MENU_ liste de contacts par page",
                "zeroRecords": "Aucun enregistrement",
                "infoEmpty": "No records available",
                "search":         "",
                "paginate": {
                    "first":      "Premier",
                    "last":       "Dernier",
                    "next":       "Suivant",
                    "previous":   "Précédent"
                },
            },

            'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {'selectRow': true},
                }
            ],
            'select': {'style': 'single'},
            'order': [[1, 'asc']]
        });

        $("#checkboxtableStyleSingleUnordered_filter").css("display", "none"); // hidden search input

    });
</script>
