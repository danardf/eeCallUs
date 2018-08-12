# eeCallUs
Plugin pour boitier domotic eeDomus.

## Prérequis.
- Il vous faudra bien sure un server Asterisk version supérieure ou égale à 11.
- Voir également un serveur FreePBX version 13 ou 14.
- Avoir une base de connaissances sur Asterisk ou FreePBX.

## But de ce plugin.
- Mettre en communication deux personnes par téléphone (un interne et un externe)
- Mettre en communication par téléphone (interne ou externe) une personne et un message vocal (côté Asterisk)
- Déposer un message texte sur un téléphone interne Asterisk.

## Les scénarii

### Mettre en communication deux personnes par téléphone (un interne et un externe).
J'ai créé préalablement un numéro (dial plan) qui me diffuse un message vocal (le 291)

Mon poste interne est le 100

```
http://localhost/script/?exec=eeCall.php&ip=193.168.0.10&port=5038&user=eedomus&secret=xxxxxxxxxxx&from=100&to=291
```

### Mettre en communication par téléphone (interne ou externe) une personne et un message vocal (côté Asterisk)

```
http://localhost/script/?exec=eeCall.php&ip=193.168.0.10&port=5038&user=eedomus&secret=xxxxxxxxxxx&from=0240404040&to=291
```

### Déposer un message texte sur un téléphone interne Asterisk.

```
http://localhost/script/?exec=eeCall.php&ip=193.168.0.10&port=5038&user=eedomus&secret=xxxxxxxxxxx&from=eedomus&to=100&msg=Alerte débordement cuve&drv=sip
```
