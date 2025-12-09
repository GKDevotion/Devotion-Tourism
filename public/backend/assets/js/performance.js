var count = 2;
var limits = 500;

var count_dev_plan = 2;
var limits_dev_plan = 500;

("use strict");
// Add request input field
function add_key_goals(e) {
    var t =
        `<td>
            <textarea class="form-control" name="key_goals_item[key_goals][]" id="description" rows="2" placeholder="" tabindex="10" required=""></textarea>
        </td>
        <td class="">
            <input type="date"  class="form-control datepicker_sub" name="key_goals_item[completion_period][]" placeholder="" required  min="0"/>
        </td>
        <td> 
            <a id="add_key_goals" class="btn btn-info btn-sm" name="add-invoice-item" onClick="add_key_goals('key_goals_item')">
                <i class="fa fa-plus-square" aria-hidden="true"></i>
            </a>
            <a class="btn btn-danger btn-sm" value="" onclick="deleteRow(this)" >
                <i class="fa fa-trash" aria-hidden="true"></i>
            </a>
        </td>`;

    count == limits
        ? alert("You have reached the limit of adding " + count + " inputs")
        : $("tbody#key_goals_item").append("<tr>" + t + "</tr>");

    count++;

    $(".datepicker_sub").datetimepicker({
        timepicker: false,
        format: "Y-m-d",
    });
}

("use strict");

function deleteRow(e) {
    var t = $("#request_table > tbody > tr").length;
    if (1 == t) alert("There only one row you can't delete.");
    else {
        var a = e.parentNode.parentNode;
        a.parentNode.removeChild(a);
    }
}

("use strict");

//Add request input field
function add_dev_plans(e) {
    var t =
        `<td>
            <textarea name="dev_plan[recommend_areas][]" class="form-control" placeholder="for improvement development"></textarea>
        </td>
        <td>
            <textarea name="dev_plan[expected_outcomes][]" class="form-control" placeholder="Expected outcome"></textarea>
        </td>
        <td>
            <textarea id="responsible_person" name="dev_plan[responsible_person][]" placeholder="to assist in the achievement of the plan" class="form-control"></textarea>
        </td>
        <td>
            <input type="date" id="start_date" name="dev_plan[start_date][]" class="form-control datepicker_sub1" required>
        </td>
        <td>
            <input type="date" id="end_date" name="dev_plan[end_date][]" class="form-control datepicker_sub2" required>
        </td>
        <td> 
            <a  id="add_dev_plan" class="btn btn-info btn-sm" name="add-invoice-item" onClick="add_dev_plans('key_dev_plan_item')">
                <i class="fa fa-plus-square" aria-hidden="true"></i>
            </a> 
            <a class="btn btn-danger btn-sm" value="" onclick="deleteDevPlanRow(this)" >
                <i class="fa fa-trash" aria-hidden="true"></i>
            </a>
        </td>`;

    count_dev_plan == limits_dev_plan
        ? alert("You have reached the limit of adding "+count_dev_plan+" inputs")
        : $("tbody#key_dev_plan_item").append("<tr>" + t + "</tr>");

    count_dev_plan++;

    $(".datepicker_sub1").datetimepicker({
        timepicker: false,
        format: "Y-m-d",
    });

    $(".datepicker_sub2").datetimepicker({
        timepicker: false,
        format: "Y-m-d",
    });
}

("use strict");

function deleteDevPlanRow(e) {
    var t = $("#request_table_dev_plan > tbody > tr").length;
    if (1 == t) alert("There only one row you can't delete.");
    else {
        var a = e.parentNode.parentNode;
        a.parentNode.removeChild(a);
    }
}

$(document).on( "ready", function () {
    "use strict";

    //common assessment calculation configuration 
    $(".assessment").on( "click", function () {
        var clickId = $(this).attr( 'data-id' );
        var indicator = $(this).attr( 'data-indicator' );
        scoreCalculation( clickId+"_score", $(this).val(), "assessment_"+indicator+"_total_score", "assessment_"+indicator );
    });

    // Check contributing_score can not be more than 10
    $("#beyond_duty").on("keyup", function () {
        if ($(this).val() == "" || parseInt($(this).val()) < 0) {
            $(this).val(0);
        }

        if (parseInt($(this).val()) >= 12) {
            $(this).val(12);
        } else if (parseInt($(this).val()) >= 9) {
            $(this).val(9);
        } else if (parseInt($(this).val()) >= 6) {
            $(this).val(6);
        } else if (parseInt($(this).val()) >= 3) {
            $(this).val(3);
        } else {
            $(this).val(0);
        }
        
        // Ignore decimal numbers
        $(this).val(parseInt($(this).val()));

        var assessment_a_total_score = 0;
        $(".assessment_a").each(function () {
            assessment_a_total_score =
                assessment_a_total_score + parseInt($(this).val());
        });
        $("#assessment_a_total_score").val(assessment_a_total_score);

        update_total_score();
    });

    // Check contributing_score can not be more than 10
    $("#contributing_score").on("keyup", function () {

        if ($(this).val() == "" || parseInt($(this).val()) < 0) {
            $(this).val(0);
        }

        if (parseInt($(this).val()) >= 10) {
            $(this).val(10);
        } else if (parseInt($(this).val()) >= 9) {
            $(this).val(9);
        } else if (parseInt($(this).val()) >= 6) {
            $(this).val(6);
        } else if (parseInt($(this).val()) >= 3) {
            $(this).val(3);
        } else {
            $(this).val(0);
        }

        // Ignore decimal numbers
        $(this).val(parseInt($(this).val()));
        var assessment_b_total_score = 0;
        $(".assessment_b").each(function () {
            assessment_b_total_score =
                assessment_b_total_score + parseInt($(this).val());
        });
        $("#assessment_b_total_score").val(assessment_b_total_score);
        update_total_score();
    });

    function update_total_score() {

        var assessment_a_total_score = 0;
        $(".assessment_a").each(function () {
            assessment_a_total_score+= parseInt($(this).val());
        });

        var assessment_b_total_score = 0;
        $(".assessment_b").each(function () {
            assessment_b_total_score+= parseInt($(this).val());
        });

        var score_final = assessment_a_total_score + assessment_b_total_score;
        $("#score_a").text(assessment_a_total_score);
        $("#score_b").text(assessment_b_total_score);
        $("#score_final").text(score_final);
    }

    function scoreCalculation( scoreInputId, scoreInputVal, scoreOutputId, scoreCountClass ){
        $("#"+scoreInputId).val(scoreInputVal);

        var totalScore = 0;
        $("."+scoreCountClass).each(function () {
            totalScore+= parseInt( $(this).val() );
        });
        $("#"+scoreOutputId).val(totalScore);

        update_total_score();
    }

    if( $("#employeePerformanceForm").length > 0){
        $("#employeePerformanceForm").on( "submit", function(e) {

            var isValid = true;
        
            // Validate all input fields
            $("input[data-required='yes']").each(function() {

                if ( $(this).val().trim() === "" ) {
                    // $(this).next(".error").text("This field is required.");
                    $(this).addClass("error-border");
                    isValid = false;
                } else {
                    // $(this).next(".error").text("");
                    $(this).removeClass("error-border");
                }
            });
        
            // Validate all textareas
            $("textarea[data-required='yes']").each(function() {
                if ( $(this).val().trim() === "" ) {
                    // $(this).next(".error").text("This field is required.");
                    $(this).addClass("error-border");
                    isValid = false;
                } else {
                    // $(this).next(".error").text("");
                    $(this).removeClass("error-border");
                }
            });
        
            // Validate all select dropdowns
            $("select[data-required='yes']").each(function() {
                
                if ( ( $(this).val() == "" || $(this).val() == 0 || $(this).val() == null ) ) {
                    $(this).siblings(".error").text("Please select an option.");
                    // $(this).siblings().addClass("error-border");
                    isValid = false;
                } else {
                    $(this).removeClass("error-border");
                    // $(this).next(".error").text("");
                }
            });
        
            // If any field is invalid, prevent form submission
            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });
    }
});

