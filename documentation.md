# Esercitazione Architetture IT

Creazione di un gioco

## Pagina principale
La pagina principale deve contenere un form per inserire un nuovo giocatore (con indirizzo IP) e la tabella dei giocatori correntemente inseriti nel DB

La tabella utenti contiene: id (intero, chiave primaria autoincrementante), ip (varchar, massimo 20 caratteri, obbligatorio, non nullo, controllato sia in fe che in be, deve essere univoco nella tabella)