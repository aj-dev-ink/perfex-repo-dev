//Function to update CompareValue selection on change of field to comapre "conditionSelect"
$(document).on('change', '.clsIncrementalSection .conditionSelect', function () {
    // Get the selected value
    var selectedValue = $(this).val();
    console.log( selectedValue );return;
    // Reference to the parent div
    var parentDiv = $(this).closest('.clsIncrementalSection');
    
    // AJAX request to the backend
    $.ajax({
        url: 'your-backend-endpoint', // Replace with your backend URL
        type: 'POST',
        data: {
            selectedValue: selectedValue // Sending the selected value to the backend
        },
        success: function (response) {
            // Assuming response contains an array of options in the format [{id: 1, name: 'Option1'}, ...]
            var optionsData = response.data; // Adjust according to the actual structure of your response
            
            // Find the select box with class clsCompareValueSelect within the parent div
            var compareSelect = parentDiv.find('.clsCompareValueSelect');
            
            // Clear existing options
            compareSelect.empty();
            
            // Populate the select box with new options
            $.each(optionsData, function (index, option) {
                compareSelect.append($('<option>', {
                    value: option.id,
                    text: option.name
                }));
            });
            
            // Refresh if using a plugin like selectpicker
            compareSelect.selectpicker('refresh'); // If applicable
        },
        error: function (xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
});
