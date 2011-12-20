(function($){

    $.cyform = $.fn.cyform = function(method) {

        var cyform = this;

        this.createSubmitAction = function(onError, onSuccess) {
            var formElem = $('form', cyform);
            var method = formElem.attr('method').toUpperCase();
            var action = formElem.attr('action');
            var self = this;
            return function(event){
                event.preventDefault();
                var formData = cyform.serialize();
                if (formData != null) {
                    $.ajax(action, {
                        type: method,
                        data: formData,
                        dataType: "json",
                        success: function(resp) {
                            if (resp.form) {
                                console.log("bah")
                                onError(resp.form);
                            } else if ($.isFunction(onSuccess)) {
                                onSuccess.call(self, resp);
                            }
                        }
                    });
                }
            };
        }

        this.ajaxify = function() {
            if ($(cyform).data("cyform-ajaxified") === true)
                return; // the form has already been ajaxified - nothing to do
            $(cyform).find('form input[type=submit]')
                .click(this.createSubmitAction(function(form) {
                    $(form).cyform("ajaxify");
                }));
            
            $(cyform).data("cyform-ajaxified", true);
        }

        this.serialize = function() {
            var rval = {};
            var isEmpty = true;

            $(cyform).find('input, select, textarea').each(function(){
                var type = $(this).attr("type");
                var name = $(this).attr("name");
                switch (type) {
                    case "radio":
                        if ($(this).is(':checked')) {
                            rval[name] = $(this).val();
                        }
                        break;
                    case "checkbox":
                        if ($(this).is(":checked")) {
                            var suffix = name.substr(name.length - 2, 2);
                            if (suffix == '[]') {
                                var baseName = name.substring(0, name.length - 2);
                                if (rval[baseName] === undefined) {
                                    rval[baseName] = [ $(this).val() ];
                                } else {
                                    rval[baseName].push( $(this).val() );
                                }
                            } else {
                                rval[name] = $(this).val();
                            }
                        }
                        break;
                    default:
                        rval[name] = $(this).val();
                        break;
                }
            });

            return rval;
        }

        this.dialogify = function(buttonActions, dialogOptions, onSuccess) {
            var submitButton = $(cyform).find("input[type=submit]");
            if (submitButton.length > 1)
                throw "failed to dialogify form: multiple submit buttons found";

            dialogOptions.title = $(cyform).find("> legend").html();
            $(cyform).find("> legend").remove();
            
            if (dialogOptions.buttons === undefined) {
                var dialogButtons = {};
                $(cyform).find("input[type=button]").each(function() {
                    dialogButtons[$(this).attr("name")] = buttonActions[$(this).attr("name")];
                    $(this).remove();
                });
                dialogOptions.buttons = dialogButtons;
            }
            
            var submitButtonName = submitButton.attr("value");
            if (dialogOptions.buttons[submitButtonName] === undefined) {
                dialogOptions.buttons[submitButtonName] = this.createSubmitAction(function(form) {
                    $(cyform).dialog("close").remove();
                    console.log("closing prev dialog");
                    dialogOptions.buttons[submitButtonName] = undefined;
                    $(form).cyform("dialogify", buttonActions, dialogOptions);
                }, onSuccess);
            }
            submitButton.remove();
            cyform.dialog(dialogOptions);
        }

        var _method = arguments[0];

        var args = [];
        if (arguments.length > 1) {
            for (var i = 1; i < arguments.length; ++i) {
                args[i - 1] = arguments[i];
            }
        }

        if (this[_method] === undefined)
            throw "method '" + _method + "' does not exist";
        
        this[_method].apply(this, args);
    }
    
})(jQuery);
