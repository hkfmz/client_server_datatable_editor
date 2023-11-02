        function generateJSONFile(data, fileName) {
                // Convertir l'objet JavaScript en une chaîne JSON
                const jsonData = JSON.stringify(data);

                // Créer un objet Blob avec le contenu JSON
                const blob = new Blob([jsonData], { type: 'application/json' });

                // Créer une URL à partir du Blob
                const url = URL.createObjectURL(blob);

                // Créer un élément de lien pour télécharger le fichier JSON
                const a = document.createElement('a');
                a.href = url;
                a.download = fileName;

                // Simuler un clic sur le lien pour déclencher le téléchargement
                a.click();

                // Libérer la ressource URL
                URL.revokeObjectURL(url);
            }


        // Fonction pour initialiser les données à partir du stockage local
            function initializeDataFromLocalStorage() {
                let data = {};
                console.log('+++++++++++++ vide data:', data);

                    console.log('+++++++++++++ localStorage:', localStorage);
                    let action = 'afficher';
                    //let contenu= {};
                    
                    $.ajax({
                        url: "http://localhost:8044/server.php",
                        method: "POST",
                        data: { action: action }, // Envoyer la variable "action" au serveur
                        success: function (data) 
                        {
                            console.log("========== data server: ", data);

                            initializeDataTable(data);
                            // localStorage.clear();
                        }
                    });
                        
                return data;
            }


            // Fonction pour gérer les opérations AJAX
            function handleAjaxAction(method, url, requestData, callbackSuccess, callbackError, todoData) {

                let output = {
                    data: []
                };

                console.log('+++++++++++++ vide output:', output);

                if (requestData.action === 'create') {
                    let dateKey = +new Date();
                    console.log('============== dateKey', dateKey);

                    for (const [key, value] of Object.entries(requestData.data)) {
                        let id = dateKey + '' + key;
                        value.DT_RowId = id;
                        todoData[id] = value;
                        output.data.push(value);

                        console.log('============== output-DATA', output.data[0]);
                        console.log('============== id', id);
                    }

                    let content = output.data[0];
                    console.log("============= create content ====: ", content);
                }

                if (requestData.action === 'edit') {
                    for (const [id, value] of Object.entries(requestData.data)) {
                        value.DT_RowId = id;
                        Object.assign(todoData[id], value);
                        output.data.push(todoData[id]);
                    }

                     let content = output.data[0];
                    console.log("============= edit content ====: ", content);
                }

                if (requestData.action === 'remove') {

                    for (const [id, value] of Object.entries(requestData.data)) {
                        value.DT_RowId = id;
                        output.data.push(todoData[id]);
                    }
                    
                    for (const id of Object.keys(requestData.data)) {
                        // Supprimez l'élément
                        delete todoData[id];
                    }
                    
                }

                console.log('+++++++++++++ todoData:', todoData);

                localStorage.setItem('todo', JSON.stringify(todoData));

                console.log('+++++++++++++ pas vide output:', output.data[0]);
                callbackSuccess(output);

                let action = requestData.action;
                let content = output.data[0];


                console.log('+++++++++++++ action:', action);
                console.log('+++++++++++++ content:', content);

                 $.ajax({
                        url: "http://localhost:8044/server.php",
                        method: "POST",
                        data: { action: action, content: content}, // Envoyer la variable "action" et contenu au serveur
                        success: function (data) 
                        {
                            console.log("========== data server2: ", data);
                            // localStorage.clear();
                        }
                    });

            }


            
            // Fonction pour initialiser DataTable
            function initializeDataTable(todoData) {
                const editor = new DataTable.Editor({
                    table: '#example',
                    fields: [{
                        label: 'Élément :',
                        name: 'item'
                    }, {
                        label: 'Statut :',
                        name: 'status',
                        type: 'radio',
                        def: 'À faire',
                        options: ['À faire', 'Terminé']
                    }],
                    ajax: function (method, url, requestData, callbackSuccess, callbackError) {
                        handleAjaxAction(method, url, requestData, callbackSuccess, callbackError, todoData);
                    }
                });

                new DataTable('#example', {
                    buttons: [{
                        extend: 'create',
                        editor
                    }, {
                        extend: 'edit',
                        editor
                    }, {
                        extend: 'remove',
                        editor
                    }],
                    columns: [{
                        data: 'item'
                    }, {
                        data: 'status'
                    }],
                    data: Object.values(todoData),
                    dom: 'Bfrtip',
                    select: true
                });
            }

            // Point d'entrée du programme
            const todoData = initializeDataFromLocalStorage();
            // initializeDataTable(todoData);