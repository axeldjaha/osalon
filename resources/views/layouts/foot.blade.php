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
    $(document).ready(function () {

        $('#datatable').DataTable({
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
            //"order": [[ 0, "desc" ]],
            "order": [] //disable default ordering
        });

        $("#datatable_filter").css("display", "none"); // hidden search input

        window.filterTable = function(input)
        {
            $('#datatable').DataTable().search($(input).val()).draw();
        };

    });
</script>
