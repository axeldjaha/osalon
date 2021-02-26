/**
 * Created by AXEL DJAHA on 05/02/2018.
 */
$(document).ready(function()
{
    var messageInput = $('#message');
    messageInput.trigger('input');

    var charLimit = $('#charLimit');

    var charCounterField = $('#charCounter');

    var partCounterField = $('#partCounter');

    var recipientCounterField = $('#recipientCounter');

    var smsVolumeCounterField = $('#smsVolumeCounter');

    var telephoneSwitcher = $('#telephoneSwitcher');

    var phoneInputWrapper = $('#phoneInputWrapper');

    /**
     * Nombre de destinataires cochés
     * @type {number}
     */
    var recipientsCount = 0;

    /**
     * A la saisie du message, MAJ des compteurs
     */
    messageInput.on('input', function()
    {
        //todo: uncomment line below if needed message without accent
        //messageInput.val(removeAccents(messageInput.val()));
        updateCounters();
    });

    window.removeAccents = function removeAccents(string)
    {
        var accents    = 'ÀÁÂÃÄÅàáâãäåÒÓÔÕÕÖØòóôõöøÈÉÊËèéêëðÇçÐÌÍÎÏìíîïÙÚÛÜùúûüÑñŠšŸÿýŽž';
        var accentsOut = "AAAAAAaaaaaaOOOOOOOooooooEEEEeeeeeCcDIIIIiiiiUUUUuuuuNnSsYyyZz";
        string = string.split('');
        var strLen = string.length;
        var i, x;
        for (i = 0; i < strLen; i++)
        {
            if ((x = accents.indexOf(string[i])) != -1)
            {
                string[i] = accentsOut[x];
            }
        }
        return string.join('');
    };

    /**
     * MAJ des compteurs
     *
     * - nombre de caractères,
     * - nombre de paquets
     * - nombre de destinataires
     * - nombre de SMS total
     */
    window.updateCounters = function ()
    {
        var sms = SmsCounter.count(messageInput.val());
        /**
         * Object returned
         * {
                encoding: "GSM_7BIT",
                length: 18,
                messages: 1,
                per_message: 160,
                remaining: 142
            }
         */

        var perMessage = null;
        var limit = null;

        switch (sms.encoding)
        {
            case "GSM_7BIT":
            case "GSM_7BIT_EX":
                limit = 480; //SMS part: 4
                perMessage = sms.length > 160 ? 153 : 160;
                break;
            case "UTF16":
                limit = 480; //SMS part: 8
                perMessage = sms.length > 70 ? 67 : 70;
                break;
        }

        var messagePart = sms.length == 0 ? 1 : Math.ceil(sms.length / perMessage);

        //MAJ compteur de caractères
        charCounterField.text(sms.length);

        //MAJ indicateur de limite
        messageInput.attr('maxlength', limit);
        charLimit.text(limit);

        //MAJ compteur de paquets
        partCounterField.text(messagePart);

        //Nombre de destinataires
        //recipientsCount = telephoneSwitcher.is(':checked') ? 1 : $('option:selected', $("select[name=destinataire]")).attr('data-size');

        //MAJ compteur de destinataires
        recipientCounterField.text(getRecipients());

        //MAJ compteur du SMS total à envoyer
        smsVolumeCounterField.text(numeral(messagePart * getRecipients()).format('0,0'));
    };

    window.switchToSingle = function () {
        if(telephoneSwitcher.is(':checked'))
        {
            recipientsCount = 1;
            phoneInputWrapper.show();
            $('#recipients input[type="checkbox"]').attr("disabled", true);
            $('#recipients input[type="checkbox"]:first').prop("checked", false);
            $('#recipients').DataTable().$("input[type=checkbox]").prop("checked", false);
            $('#recipients').DataTable().$("tr").removeClass("selected");
            $("input[name=filter]").attr("disabled", true);
        }
        else
        {
            recipientsCount = 0;
            phoneInputWrapper.hide();
            $('#recipients input[type="checkbox"]').attr("disabled", false);
            $("input[name=filter]").attr("disabled", false);

        }
        updateCounters();
    };

    /**
     * Renvoie le nombre de destinataites
     *
     * @returns {number}
     */
    function getRecipients()
    {
        return telephoneSwitcher.is(':checked') ? 1 : recipientsCount;
    }

    window.onRowClick = function (row) {
        var recipients = Number(row.find("span[class=count]").html());
        if(row.find('input[type="checkbox"]').is(":checked"))
        {
            recipientsCount += recipients;
        }
        else
        {
            recipientsCount -= recipients;
        }
        updateCounters();
    };

    window.setRecipients = function (size) {
        recipientsCount = size;
        updateCounters();
    };

    window.loadRecipients = function (url) {
        recipientsCount = 0;
        updateCounters();
        $('#recipients').DataTable().ajax.url(url).load();
    };

});
