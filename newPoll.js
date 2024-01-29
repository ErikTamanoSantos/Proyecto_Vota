$(function() {
    $("#addAnswer").click(addAnswer)
    $("#removeAnswer").click(removeAnswer)
    $("#questionImageButton").on("click", function() {
        $("#questionImage").trigger("click")
    })
    $("#answer1ImageButton").on("click", function() {
        $("#answer1Image").trigger("click")
    })
    $("#answer2ImageButton").on("click", function() {
        $("#answer2Image").trigger("click")
    })
})

function addAnswer() {
    let answersAmount = $("#answerContainer > div").length
    $("#answerContainer").append(`
        <div>
            <input type="text" placeholder="Respuesta ${answersAmount+1}" name="answers[]">
            <div id="answer${answersAmount+1}ImageButton"><i class="fa-regular fa-image" style="color: #ffffff;"></i></div>
            <input type="file" id="answer${answersAmount+1}Image" name="answerImage${answersAmount+1}" accept="image/png, image/gif, image/jpeg" >
        </div>
    `)
    $(`#answer${answersAmount+1}ImageButton`).on("click", function() {
        $(`#answer${answersAmount+1}Image`).trigger("click")
    })
    $("#removeAnswer").prop("disabled", false)
    if ($("#answerContainer > div").length >= 100) {
        $("#addAnswer").prop("disabled", true)
    }
}

function removeAnswer() {
    $("#answerContainer > div:last-child").remove()
    $("#addAnswer").prop("disabled", false)
    if ($("#answerContainer > div").length <= 2) {
        $("#removeAnswer").prop("disabled", true)
    }
}