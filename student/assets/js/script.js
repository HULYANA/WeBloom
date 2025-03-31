//sticky top
const scrollToTopBtn = document.getElementById('topBtn');

window.onscroll = function() {
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        scrollToTopBtn.style.display = "block";  
    } else {
        scrollToTopBtn.style.display = "none";  
    }
};

scrollToTopBtn.onclick = function() {
    window.scrollTo({
        top: 0,
        behavior: "smooth" 
    });
};

//orbs
const orbs = [];
const orbCount = 20;
const avoidanceDistance = 200; 

const fixedColors = [
    '#400d64', 
    '#7b13ff', 
    '#ff82f3'
]

const createOrbs = () => {
    const orbContainer = document.getElementById('orbContainer'); 
    for (let i = 0; i < orbCount; i++) {
        const orb = document.createElement('div');
        orb.classList.add('orb');
        const size = Math.random() * 380; 
        orb.style.width = `${size}px`;
        orb.style.height = `${size}px`;

        orb.style.backgroundColor = fixedColors[Math.floor(Math.random() * fixedColors.length)];

        orb.style.left = `${Math.random() * (orbContainer.clientWidth - size)}px`;
        orb.style.top = `${Math.random() * (orbContainer.clientHeight - size)}px`;
        orbContainer.appendChild(orb); 
        orbs.push(orb);
    }
};

const updateOrbs = (mouseX, mouseY) => {
    orbs.forEach(orb => {
        const rect = orb.getBoundingClientRect();
        const orbX = rect.left + rect.width / 2;
        const orbY = rect.top + rect.height / 2;

        const deltaX = mouseX - orbX;
        const deltaY = mouseY - orbY;
        const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);

        if (distance < avoidanceDistance) {
            const moveX = (deltaX / distance) * avoidanceDistance; 
            const moveY = (deltaY / distance) * avoidanceDistance;
            orb.style.transform = `translate(${-moveX}px, ${-moveY}px)`; 
        } else {
            orb.style.transform = 'translate(0, 0)'; 
        }
    });
};

window.addEventListener('mousemove', (event) => {
    const mouseX = event.clientX; 
    const mouseY = event.clientY; 
    updateOrbs(mouseX, mouseY);
});

window.onload = () => {
    createOrbs();
};

//pagination
let currentPage = 1;

function updatePageCounter() {
    const pageCounter = document.querySelector('.page-counter'); 
    pageCounter.textContent = `${currentPage} / ${totalPages}`;
}

function showPage(pageNumber) {
    const cards = document.querySelectorAll('.survey-card');
    cards.forEach(card => {
        const cardPage = parseInt(card.getAttribute('data-page'), 10);
        card.style.display = (cardPage === pageNumber) ? 'block' : 'none';
    });
    updateNavigationButtons();
}

function updateNavigationButtons() {
    const prevButton = document.querySelector('.left-arrow');
    const nextButton = document.querySelector('.right-arrow');

    prevButton.disabled = currentPage === 1;
    nextButton.disabled = currentPage === totalPages;

    prevButton.style.opacity = currentPage === 1 ? 0.5 : 1;
    nextButton.style.opacity = currentPage === totalPages ? 0.5 : 1;
}

document.addEventListener("DOMContentLoaded", () => {
    showPage(currentPage);
    updatePageCounter();

    document.querySelector('.left-arrow').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--; 
            showPage(currentPage);
            updatePageCounter();
        }
    });

    document.querySelector('.right-arrow').addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++; 
            showPage(currentPage);
            updatePageCounter();
        }
    });
});

//search bar
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchBtn');
    const cardsContainer = document.getElementById('surveyCardContainer');
    const surveyCards = cardsContainer.querySelectorAll('.survey-card');
    const noResultsMessage = document.createElement('div');
    noResultsMessage.classList.add('no-results-message');
    noResultsMessage.textContent = 'No match found for "';
    
    let currentSearchPage = 1;
    const cardsPerPage = 4;

    function filterCards() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        let matches = [];

        surveyCards.forEach(card => {
            const title = card.getAttribute('data-title').toLowerCase();
            if (title.includes(searchTerm)) {
                matches.push(card);
            }
        });

        displaySearchResults(matches);
    }

    function displaySearchResults(matches) {
        const totalPages = Math.ceil(matches.length / cardsPerPage);
        updatePageCounter(totalPages);

        surveyCards.forEach(card => {
            card.style.display = 'none';
        });

        const startIndex = (currentSearchPage - 1) * cardsPerPage;
        const endIndex = startIndex + cardsPerPage;

        matches.slice(startIndex, endIndex).forEach(card => {
            card.style.display = 'block';
        });

        if (matches.length === 0) {
            noResultsMessage.textContent = `No match found for "${searchInput.value}"`;
            if (!cardsContainer.contains(noResultsMessage)) {
                cardsContainer.appendChild(noResultsMessage);
            }
            updateNavigationButtons(0);
        } else {
            if (cardsContainer.contains(noResultsMessage)) {
                cardsContainer.removeChild(noResultsMessage);
            }
            updateNavigationButtons(totalPages);
        }
    }

    function updatePageCounter(totalPages) {
        const pageCounter = document.querySelector('.page-counter'); 
        pageCounter.textContent = `${currentSearchPage} / ${totalPages}`;
    }

    function updateNavigationButtons(totalPages) {
        const prevButton = document.querySelector('.left-arrow');
        const nextButton = document.querySelector('.right-arrow');

        if (totalPages === 0) {
            prevButton.disabled = true;
            nextButton.disabled = true;
            prevButton.style.opacity = 0.5;
            nextButton.style.opacity = 0.5;
            return;
        }

        prevButton.disabled = currentSearchPage === 1;
        nextButton.disabled = currentSearchPage === totalPages;

        prevButton.style.opacity = currentSearchPage === 1 ? 0.5 : 1;
        nextButton.style.opacity = currentSearchPage === totalPages ? 0.5 : 1;
    }

    searchButton.addEventListener('click', () => {
        currentSearchPage = 1;
        filterCards();
    });

    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            currentSearchPage = 1;
            filterCards();
        }
    });

    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.trim().toLowerCase();
        if (searchTerm === '') {
            showAllCards();
            updateNavigationButtons(totalPages)
        } else {
            currentSearchPage = 1;
            filterCards();
        }
    });

    document.querySelector('.left-arrow').addEventListener('click', () => {
        if (currentSearchPage > 1) {
            currentSearchPage--; 
            filterCards();
        }
    });

    document.querySelector('.right-arrow').addEventListener('click', () => {
        const totalPages = Math.ceil(Array.from(surveyCards).filter(card => {
            const title = card.getAttribute('data-title').toLowerCase();
            return title.includes(searchInput.value.trim().toLowerCase());
        }).length / cardsPerPage);

        if (currentSearchPage < totalPages) {
            currentSearchPage++; 
            filterCards();
        }
    });
});

//parallax
window.addEventListener('scroll', () => {
    const scrollPosition = window.scrollY; 
    const accountCntr = document.querySelector('.account-cntr');

    if (accountCntr) { 
        accountCntr.style.transform = `translateY(${scrollPosition * 0.5}px)`;
    }
    // const moreInfoCntr = document.querySelector('.moreinfo-cntr');
    // moreInfoCntr.style.transform = `translateY(${scrollPosition * 0.1}px)`; 
});

//account menu
document.addEventListener("DOMContentLoaded", () => {
    const aboutBtn = document.querySelector('.about-btn');
    const actsBtn = document.querySelector('.acts-btn');
    const aboutContainer = document.querySelector('#aboutContainer');
    const activitiesContainer = document.querySelector('#activitiesContainer');

    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section');

    if (section === 'activities') {
        actsBtn.classList.add('active');
        aboutBtn.classList.remove('active');
        activitiesContainer.style.display = 'block';
        aboutContainer.style.display = 'none';
        activitiesContainer.scrollIntoView({ behavior: 'smooth' });
    }

    aboutBtn.addEventListener('click', () => {
        aboutBtn.classList.add('active');
        actsBtn.classList.remove('active');
        aboutContainer.style.display = 'block';
        activitiesContainer.style.display = 'none';
    });

    actsBtn.addEventListener('click', () => {
        actsBtn.classList.add('active');
        aboutBtn.classList.remove('active');
        activitiesContainer.style.display = 'block';
        aboutContainer.style.display = 'none';
    });
});

//logout
document.addEventListener("DOMContentLoaded", () => {
    const logoutButton = document.getElementById("logoutBtn");
    const logoutOverlay = document.getElementById("logoutOverlay");
    const confirmLogout = document.getElementById("confirmLogout");
    const cancelLogout = document.getElementById("cancelLogout");

    console.log("Overlay classes on load:", logoutOverlay.classList);

    logoutButton.addEventListener("click", (event) => {
        event.preventDefault(); 
        logoutOverlay.style.display = "flex";
    });

    confirmLogout.addEventListener("click", () => {
        window.location.href = "../../shared/logout.php"; 
    });

    cancelLogout.addEventListener("click", () => {
        logoutOverlay.style.display = "none";
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const responsesBtn = document.getElementById("responsesBtn");
    const missedBtn = document.getElementById("missedBtn");
    const responseContainer = document.getElementById("responseContainer");
    const missedContainer = document.getElementById("missedContainer");

    console.log("Document has loaded");

    loadResponses();

    responsesBtn.addEventListener("click", () => {
        console.log("Responses button clicked");
        responsesBtn.classList.add("active");
        missedBtn.classList.remove("active");
        responseContainer.style.display = "block";
        missedContainer.style.display = "none"; 
        responsesBtn.style.color = "#e559fb";
        missedBtn.style.color = "#fff";
        loadResponses();
    });

    missedBtn.addEventListener("click", () => {
        console.log("Missed button clicked");
        missedBtn.classList.add("active");
        responsesBtn.classList.remove("active");
        missedContainer.style.display = "block";
        responseContainer.style.display = "none"; 
        loadMissed();
        missedBtn.style.color = "#e559fb";
        responsesBtn.style.color = "#fff";
    });

    function loadResponses() {
        console.log("Loading responses");
        fetch("../../student/backend/php/getResponses.php")
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(data => {
                console.log("Responses loaded");
                responseContainer.innerHTML = data; 
            })
            .catch(error => {
                console.error("Error loading responses:", error);
                responseContainer.innerHTML = "<p>Failed to load responses.</p>";
            });
    }

    function loadMissed() {
        console.log("Loading missed responses");
        fetch("../../student/backend/php/getMisssed.php")
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(data => {
                console.log("Missed responses loaded:", data); // Check what is returned
                missedContainer.innerHTML = data; 
            })
            .catch(error => {
                console.error("Error loading missed responses:", error);
                missedContainer.innerHTML = "<p>Failed to load missed responses.</p>";
            });
    }
});
function viewResponse() {
    window.location.href = "../../student/view/responses.php";
}

document.addEventListener("DOMContentLoaded", function() {
    const questionnaireID = new URLSearchParams(window.location.search).get('questionnaireID');
    
    if (questionnaireID) {
        fetch(`../../student/backend/php/getAnswers.php?questionnaireID=${questionnaireID}`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('questionnaireContainer');

                if (data.error) {
                    container.innerHTML = `<p>${data.error}</p>`;
                } else if (data.message) {
                    container.innerHTML = `<p>${data.message}</p>`;
                } else {
                    data.forEach(question => {
                        const questionDiv = document.createElement('div');
                        questionDiv.classList.add('question-container');
                        questionDiv.innerHTML = `
                            <div class="question-row">
                                <strong>Question:</strong><br>
                                <span>${question.questionText}</span>
                            </div>
                            <div class="answer-row"></div>
                        `;
                        container.appendChild(questionDiv);
                    });
                }
            })
            .catch(error => {
                const container = document.getElementById('questionnaireContainer');
                container.innerHTML = `<p>Error: ${error.message}</p>`;
            });
    } else {
        const container = document.getElementById('questionnaireContainer');
        container.innerHTML = `<p>No questionnaire ID provided.</p>`;
    }
});


//AJAX
fetch('../../student/backend/getCredentials.php')
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error(data.error);
        } else {
            document.querySelector('.surveyh-container h1').textContent = data.username;
            document.querySelector('.surveyh-container h3').textContent = `${data.programName} | ${data.evalautionPeriod}`;
        }
    })
    .catch(error => console.error('Fetch error:', error));