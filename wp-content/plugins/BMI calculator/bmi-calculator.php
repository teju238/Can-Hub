<?php
/*
Plugin Name: BMI Calculator
Plugin URI: https://example.com/
Description: A plugin that calculates BMI
Version: 1.0
Author: Your Name
Author URI: https://example.com/
License: GPL2
*/

// Add the BMI calculator shortcode

function bmi_calculator_shortcode() {
    ob_start();
    ?>
    <form id="bmi-calculator-form">
        <label for="height">Height (cm)</label>
        <input type="number" name="height" id="height" required>
        <label for="weight">Weight (kg)</label>
        <input type="number" name="weight" id="weight" required>
        <button type="submit">Calculate BMI</button>
    </form>
    <div id="bmi-result"></div>
    <script>
        jQuery(document).ready(function($) {
            $('#bmi-calculator-form').on('submit', function(e) {
                e.preventDefault();
                var data = {
                    action: 'calculate_bmi',
                    height: $('#height').val(),
                    weight: $('#weight').val()
                };
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        $('#bmi-result').html(response);
                    }
                });
            });
        });
    </script>
    <style>
#bmi-calculator-form label {
    display: block;
    font-size: 16px;
    margin-bottom: 5px;
}

#bmi-calculator-form input[type="number"] {
    display: block;
    width: 100%;
    max-width: 300px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 15px;
}

#bmi-calculator-form button[type="submit"] {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

#bmi-calculator-form button[type="submit"]:hover {
    background-color: #3e8e41;
}

#bmi-result {
    margin-top: 20px;
    font-size: 18px;
    font-weight: bold;
    color: #4CAF50;
}

</style>
    <?php
    return ob_get_clean();
}
add_shortcode('bmi_calculator', 'bmi_calculator_shortcode');

// Calculate BMI and return the result
function calculate_bmi() {
    $height = $_POST['height'] / 100; // Convert cm to m
    $weight = $_POST['weight'];
    $bmi = $weight / ($height * $height);
    $result = '';
    if ($bmi < 18.5) {
        $result = 'Underweight';
    } elseif ($bmi >= 18.5 && $bmi < 25) {
        $result = 'Normal weight';
    } elseif ($bmi >= 25 && $bmi < 30) {
        $result = 'Overweight';
    } elseif ($bmi >= 30) {
        $result = 'Obese';
    }
    echo 'Your BMI is ' . $bmi . ' (' . $result . ')';
    die();
}
add_action('wp_ajax_calculate_bmi', 'calculate_bmi');
add_action('wp_ajax_nopriv_calculate_bmi', 'calculate_bmi');
