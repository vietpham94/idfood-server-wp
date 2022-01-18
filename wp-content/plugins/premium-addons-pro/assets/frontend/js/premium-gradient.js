(function ($) {

    if ($('.premium-gradient-yes').length) {

        Object.values(window.scopes_array).forEach(function ($scope) {
            premiumGradientHandler($scope);
        });
        // Object.keys(window.scopes_array).forEach(function (i) {
        //     $scope = window.scopes_array[i];
        //     premiumGradientHandler($scope);
        // });

    }

    function premiumGradientHandler($scope) {

        var target = $scope,
            sectionId = target.data("id"),
            settings = {},
            editMode = elementorFrontend.isEditMode();

        if (editMode) {
            settings = generateEditorSettings(sectionId);
        } else {
            settings = generatePreviewSettings();
        }

        if (!settings) {
            return false;
        }

        generateGradient();

        function generateEditorSettings(targetId) {
            var editorElements = null,
                sectionData = {},
                sectionGradientData = {};

            if (!window.elementor.hasOwnProperty("elements")) {
                return false;
            }

            editorElements = window.elementor.elements;

            if (!editorElements.models) {
                return false;
            }

            $.each(editorElements.models, function (index, elem) {
                if (targetId === elem.id) {
                    sectionData = elem.attributes.settings.attributes;
                } else if (
                    elem.id === target.closest(".elementor-top-section").data("id")
                ) {
                    $.each(elem.attributes.elements.models, function (index, col) {
                        $.each(col.attributes.elements.models, function (index, subSec) {
                            sectionData = subSec.attributes.settings.attributes;
                        });
                    });
                }
            });

            if (!sectionData.hasOwnProperty("premium_gradient_colors_repeater")) {
                return false;
            }

            sectionGradientData =
                sectionData["premium_gradient_colors_repeater"].models;

            if (undefined === sectionGradientData || 0 === sectionGradientData.length) {
                return false;
            }

            settings.angle = sectionData["premium_gradient_angle"];
            settings.colorData = [];

            $.each(sectionGradientData, function (index, obj) {
                settings.colorData.push(obj.attributes);
            });

            if (0 !== Object.keys(settings).length) {
                return settings;
            }

            return false;
        }

        function generatePreviewSettings() {
            var previewSettings = target.data("gradient");

            if (!previewSettings) {
                return false;
            }

            settings.angle = previewSettings["angle"];
            settings.colorData = [];

            $.each(previewSettings["colors"], function (index, color) {
                settings.colorData.push(color);
            });

            if (0 !== Object.keys(settings).length) {
                return settings;
            }
        }

        function generateGradient() {
            var gradientStyle = "linear-gradient(" + settings.angle + "deg,";

            $.each(settings.colorData, function (index, layout) {
                if (null !== layout["premium_gradient_colors"]) {
                    gradientStyle += layout["premium_gradient_colors"] + ",";
                }
            });

            gradientStyle += ")";

            gradientStyle = gradientStyle.replace(",)", ")");

            //      if (target.hasClass("premium-gradient-move")) {
            target.css("background", gradientStyle);
            //      }
        }
    };

})(jQuery);