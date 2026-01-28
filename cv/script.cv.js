document.querySelectorAll('input, textarea').forEach(element => {
element.addEventListener('input', updateCVPreview);
        });
        
        document.getElementById('add-experience').addEventListener('click', function() {
            const experiencesDiv = document.getElementById('experiences');
            const newExp = document.querySelector('.experience-form').cloneNode(true);
            
            newExp.querySelectorAll('input, textarea').forEach(input => {
                input.value = '';
            });
            
            experiencesDiv.appendChild(newExp);
            attachExperienceListeners(newExp);
        });
        
        document.getElementById('add-education').addEventListener('click', function() {
            const educationsDiv = document.getElementById('educations');
            const newEdu = document.querySelector('.education-form').cloneNode(true);
            
            newEdu.querySelectorAll('input').forEach(input => {
                input.value = '';
            });
            
            educationsDiv.appendChild(newEdu);
            attachEducationListeners(newEdu);
        });

        document.getElementById('download-cv').addEventListener('click', function() {
            const element = document.getElementById('cv-preview');
            const opt = {
                margin: 10,
                filename: 'mon_cv_professionnel.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            
            html2pdf().set(opt).from(element).save();
        });
        
        function updateCVPreview() {
            document.getElementById('preview-name').textContent = document.getElementById('fullname').value || 'Votre Nom Complet';
            document.getElementById('preview-title').textContent = document.getElementById('jobtitle').value || 'Titre du poste';
            document.getElementById('preview-about').textContent = document.getElementById('about').value || 'Une brève description de vous-même apparaîtra ici.';
            
            const email = document.getElementById('email').value || 'email@exemple.com';
            const phone = document.getElementById('phone').value || '+33 1 23 45 67 89';
            const address = document.getElementById('address').value || 'Ville, Pays';
            document.getElementById('preview-contact').textContent = `${email} | ${phone} | ${address}`;
            
            const skills = document.getElementById('skills').value || 'HTML, CSS, JavaScript';
            const skillsArray = skills.split(',').map(skill => skill.trim());
            const skillsHtml = skillsArray.map(skill => `<span class="skill">${skill}</span>`).join('');
            document.getElementById('preview-skills').innerHTML = skillsHtml || '<span class="skill">HTML</span><span class="skill">CSS</span><span class="skill">JavaScript</span>';
        }
        
        function attachExperienceListeners(expElement) {
            expElement.querySelectorAll('input, textarea').forEach(input => {
                input.addEventListener('input', function() {
                    updateExperiencePreviews();
                });
            });
        }
        
        function attachEducationListeners(eduElement) {
            eduElement.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    updateEducationPreviews();
                });
            });
        }
        
        function updateExperiencePreviews() {
            const experienceForms = document.querySelectorAll('.experience-form');
            let experiencesHtml = '';
            
            experienceForms.forEach(form => {
                const title = form.querySelector('.exp-title').value || 'Poste';
                const company = form.querySelector('.exp-company').value || 'Entreprise';
                const period = form.querySelector('.exp-period').value || 'Période';
                const description = form.querySelector('.exp-description').value || 'Description des responsabilités et réalisations.';
                
                experiencesHtml += `
                    <div class="experience-item">
                        <p class="item-title">${title}</p>
                        <p class="item-subtitle">${company}</p>
                        <p class="item-date">${period}</p>
                        <p>${description}</p>
                    </div>
                `;
            });
            
            document.getElementById('preview-experiences').innerHTML = experiencesHtml || `
                <div class="experience-item">
                    <p class="item-title">Développeur Frontend</p>
                    <p class="item-subtitle">Nom de l'entreprise</p>
                    <p class="item-date">Jan 2020 - Présent</p>
                    <p>Description des responsabilités et réalisations.</p>
                </div>
            `;
        }
        
        function updateEducationPreviews() {
            const educationForms = document.querySelectorAll('.education-form');
            let educationsHtml = '';
            
            educationForms.forEach(form => {
                const degree = form.querySelector('.edu-degree').value || 'Diplôme';
                const school = form.querySelector('.edu-school').value || 'Établissement';
                const period = form.querySelector('.edu-period').value || 'Période';
                
                educationsHtml += `
                    <div class="education-item">
                        <p class="item-title">${degree}</p>
                        <p class="item-subtitle">${school}</p>
                        <p class="item-date">${period}</p>
                    </div>
                `;
            });
            
            document.getElementById('preview-educations').innerHTML = educationsHtml || `
                <div class="education-item">
                    <p class="item-title">Master en Informatique</p>
                    <p class="item-subtitle">Nom de l'université</p>
                    <p class="item-date">2019 - 2021</p>
                </div>
            `;
        }
        
        attachExperienceListeners(document.querySelector('.experience-form'));
        attachEducationListeners(document.querySelector('.education-form'));