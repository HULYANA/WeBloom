const questionsPerPage = 3;
let currentPage = 1;
const selectedChoices = new Array(questions.length).fill(null);

function getStorageKey(key) {
    return `${questionnaireID}_${key}`;
}

function displayQuestions(page) {
    const start = (page - 1) * questionsPerPage;
    const end = start + questionsPerPage;
    const questionContainer = document.getElementById('question-container');
    questionContainer.innerHTML = '';

    questions.slice(start, end).forEach((q, index) => {
        const questionDiv = document.createElement('div');
        questionDiv.classList.add('question');

        const questionText = document.createElement('p');
        questionText.classList.add('question-text');
        questionText.innerText = q.questionText;
        questionDiv.appendChild(questionText);

        q.choices.forEach(choice => {
            const choiceLabel = document.createElement('label');
            choiceLabel.classList.add('choice');

            const choiceInput = document.createElement('input');
            choiceInput.type = 'radio';
            choiceInput.name = `question_${start + index + 1}`;
            choiceInput.value = choice;
            choiceInput.classList.add('choice-input');

            if (selectedChoices[start + index] === choice) {
                choiceInput.checked = true;
            }

            choiceInput.addEventListener('change', () => {
                selectedChoices[start + index] = choice;
                updateProgressBar();
                sessionStorage.setItem(getStorageKey('selectedChoices'), JSON.stringify(selectedChoices));
            });

            choiceLabel.appendChild(choiceInput);
            const choiceText = document.createTextNode(choice);
            choiceLabel.appendChild(choiceText);

            questionDiv.appendChild(choiceLabel);
        });

        questionContainer.appendChild(questionDiv);
    });

    document.getElementById('prev-btn').disabled = page === 1;
    document.getElementById('next-btn').disabled = end >= questions.length;

    updateProgressBar();
}

function updateProgressBar() {
    const answeredCount = selectedChoices.filter(choice => choice !== null).length;
    const totalQuestions = questions.length;
    const progressPercentage = (answeredCount / totalQuestions) * 100;

    document.getElementById('progress-bar').style.width = progressPercentage + '%';
    document.getElementById('progress-percentage').innerText = Math.round(progressPercentage) + '%';

    const prevButton = document.getElementById('prev-btn');
    const nextButton = document.getElementById('next-btn');
    const submitButton = document.getElementById('submit-btn');
    const isLastPage = currentPage * questionsPerPage >= totalQuestions;

    prevButton.style.visibility = currentPage === 1 ? 'hidden' : 'visible';
    nextButton.style.visibility = isLastPage ? 'hidden' : 'visible';

    if (isLastPage) {
        nextButton.disabled = true;
        submitButton.style.display = progressPercentage === 100 ? 'inline-block' : 'none';
    } else {
        nextButton.onclick = nextPage;
        nextButton.disabled = false;
        submitButton.style.display = 'none';
    }
}

function nextPage() {
    if (currentPage * questionsPerPage < questions.length) {
        currentPage++;
        displayQuestions(currentPage);
        sessionStorage.setItem(getStorageKey('currentPage'), currentPage);
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayQuestions(currentPage);
        sessionStorage.setItem(getStorageKey('currentPage'), currentPage);
    }
}

function loadSavedProgress() {
    const savedAnswers = sessionStorage.getItem(getStorageKey('selectedChoices'));
    const savedPage = sessionStorage.getItem(getStorageKey('currentPage'));

    if (savedAnswers) {
        selectedChoices.length = 0;
        selectedChoices.push(...JSON.parse(savedAnswers));
    }

    if (savedPage) {
        currentPage = parseInt(savedPage);
    } else {
        currentPage = 1;
    }
}

function resetProgress() {
    selectedChoices.fill(null);
    currentPage = 1;
    sessionStorage.removeItem(getStorageKey('selectedChoices'));
    sessionStorage.removeItem(getStorageKey('currentPage'));
}

function returnHome() {
    loadSavedProgress();
    window.location.href = "../view/studHome.php";
}

function submitSurvey() {
    const answers = selectedChoices.map((choice, index) => ({ answer: choice }));

    fetch('../../student/backend/php/submitSurvey.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ questionnaireID, studentID, answers })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Survey submitted successfully!");
            sessionStorage.removeItem(getStorageKey('selectedChoices'));
            sessionStorage.removeItem(getStorageKey('currentPage'));
            window.location.href = "../view/studHome.php";
        } else {
            alert("Error submitting survey: " + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function loadQuestionnaire(newQuestionnaireID) {
    if (newQuestionnaireID !== questionnaireID) {
        questionnaireID = newQuestionnaireID;
        resetProgress();
    }
    loadSavedProgress();
    displayQuestions(currentPage);
}

document.addEventListener('DOMContentLoaded', () => {
    loadSavedProgress();
    displayQuestions(currentPage);
});
