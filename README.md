# eeCallUs V1.0
Plugin pour boitier domotic eeDomus

*(Ce plugin a été testé uniquement avec un serveur Freepbx version 14)*

## Prérequis.
- Il vous faudra bien sure un server Asterisk version supérieure ou égale à 11.
- Voir également un serveur FreePBX version 13 ou 14.
- Avoir une base de connaissances sur Asterisk ou FreePBX.
- Configurer un utilisateur AMI avec les privilèges: **message,originate** ou **all**

## But de ce plugin.
- Mettre en communication deux personnes par téléphone (un interne et un externe)
- Mettre en communication par téléphone (interne ou externe) une personne et un message vocal (côté Asterisk)
- Déposer un message texte sur un téléphone interne Asterisk.

## Les scénarii

### Mettre en communication deux personnes par téléphone (un interne et un externe).
J'ai créé préalablement un numéro (dial plan) qui me diffuse un message vocal (le 291)

Mon poste interne est le 100

```
http://localhost/script/?exec=eeCall.php&ip=193.168.0.10&port=5038&user=eedomus&secret=xxxxxxxxxxx&from=100&to=291&msg=&drv=
```

### Mettre en communication par téléphone (interne ou externe) une personne et un message vocal (côté Asterisk)

```
http://localhost/script/?exec=eeCall.php&ip=193.168.0.10&port=5038&user=eedomus&secret=xxxxxxxxxxx&from=0240404040&to=291&msg=&drv=
```

### Déposer un message texte sur un téléphone interne Asterisk.

```
http://localhost/script/?exec=eeCall.php&ip=193.168.0.10&port=5038&user=eedomus&secret=xxxxxxxxxxx&from=eedomus&to=100&msg=Alerte débordement cuve&drv=sip
```
## Les arguments 

Les arguments à passer avec l'URL sont:
- ip (L'adresse IP du serveur Asterisk ou FreePBX)
- port (Le port AMI utilisé. Si est vide **port=&** alors ce sera **5038** par défaut)
- user (Le compte AMI à utiliser)
- secret (Le mot de passe du compte AMI)
- from (L'origine de l'appel : **Interne** ou **Externe**)
- to (La destination : **Interne uniquement**)
- msg (Vide si l'on utilise le script pour générer un appel. Ou y mettre un court message)
- drv (Vide si l'on utilise le script pour générer un appel. Ou y mettre la technologie à utiliser en **minuscule** : sip, pjsip)

## Les liens utiles

https://www.freepbx.org/

https://www.voip-info.org/asterisk-manager-api/

https://www.voip-info.org/asterisk-config-managerconf/

https://wiki.asterisk.org/wiki/display/AST/Home

