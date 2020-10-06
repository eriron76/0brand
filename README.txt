
Ogni software aziendale ha un supporto integrato per gli organigrammi, al fine di rappresentare gerarchie e ruoli all'interno di un'azienda. Un modo comune e conveniente per gestirli con una struttura ad albero nei database relazionali è il modello " Nested Set "


Specifica dei requisiti:
un'applicazione front end deve recuperare i nodi dell'organigramma (vedere immagni nella dir data) e mostrarli con una visualizzazione ad albero e, quindi, dipende da un'API di backend per ottenere in modo efficiente tali dati.
Implementare uno script php, api.php, per restituire i nodi dell'organigramma potendo specifica da quale livello partire e permettendo la paginazione.

Lo script verrà chiamato tramite HTTP (metodo GET) attraverso un server web Apache e riceverà i seguenti parametri di input:
- node_id (numero intero, obbligatorio ): l'ID univoco del nodo selezionato.
- language (enum, obbligatorio): identificatore della lingua. Valori possibili: "english", "italian".
- search_keyword (stringa, opzionale): un termine di ricerca utilizzato per filtrare i risultati. Se fornito, limita i risultati in tutti i nodi figli sotto node_id il cui nodeName nella lingua data contiene la parola chiave di ricerca (senza distinzione tra maiuscole e minuscole) .
- page_num (numero intero, opzionale): l'identificatore 0-based della pagina da recuperare. Se non specificato il valore predefinito è 0.
- page_size (numero intero, opzionale): la dimensione, in termini di record, della pagina da recuperare, compresa tra 0 e 1000. Se non fornito, il valore predefinito è 100.

L'API dovrebbe restituire un JSON con i seguenti campi:
- nodes (array, obbligatorio ): 0 o più nodi che rispettano le seguenti condizioni. Ogni nodo deve contenere:
- node_id (integer, obbligatorio ): l'ID univoco del nodo selezionato.
- name (string, obbligatorio ): il nome del nodo tradotto nel linguaggio richiesto.
- children_count (integer, obbligatorio ): il numero di figli di questo nodo.
- error (string, opzionale): se è avvenuto un errore compilare con il messaggio dell’errore.

Vincoli:
la soluzione proposta deve controllare che tutti i parametri richiesti siano passati e validi e restituisca, in caso di errore, i seguenti messaggi:
- "ID nodo non valido" (se node_id non viene trovato).
- "Parametri obbligatori mancanti" (se un qualsiasi parametro di input richiesto non viene passato o ha valore vuoto).
- "Numero di pagina richiesto non valido" (se page_num non è un numero 0-based valido).
- "Richiesto formato pagina non valido" (se page_size non rientra nell'intervallo di validità).
- Nessun framework è consentito (solo per posizioni Senior e Mid)
- utilizzare l'estensione "pdo_mysql" per eseguire query verso il database.

Struttura:
- api.php (punto di ingresso)
- config.php (file di configurazione contenente le credenziali di accesso al database ed eventuali configurazioni)
- tables.sql (script SQL di definizione delle tabelle).
- data.sql (script SQL per l'inserimento dei dati) 