$(function() {
    $("#addAnswer").click(addAnswer)
    $("#removeAnswer").click(removeAnswer)
})

function addAnswer() {
    let answersAmount = $("#answerContainer input").length
    $("#answerContainer").append(`<input type="text" placeholder="Respuesta ${answersAmount+1}" name="answers[]">`)
    $("#removeAnswer").prop("disabled", false)
    if ($("#answerContainer input").length >= 100) {
        $("#addAnswer").prop("disabled", true)
    }
}

function removeAnswer() {
    $("#answerContainer input:last-child").remove()
    $("#addAnswer").prop("disabled", false)
    if ($("#answerContainer input").length <= 2) {
        $("#removeAnswer").prop("disabled", true)
    }
}