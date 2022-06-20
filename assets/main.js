function editProject(id)
{
        
    $.ajax({
        url: "/project/" + id,
        method: "GET",
        success: function(response) {
            let project = response
            $("#alert-div").html("");
            $("#error-div").html("");   
            $("#update_id").val(project.id);
            $("#name").val(project.name);
            $("#description").val(project.description);
            $("#form-modal").modal('show'); 
        },
        error: function(response) {
            console.log(response.responseJSON)
        }
    });
}