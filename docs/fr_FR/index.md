# Plugin Wes

Plugin permettant d’utiliser le WES de CartElectronics

# Configuration du Wes

Après installation du plugin, il vous suffit de l’activer :

![mobile1](../images/configuration.png)

**Configuration**

Pour configurer le plugin, vous devez ajouter le Wes.

Pour Ajouter un wes : **Plugins** → **Protocole Domotique** → **Wes** → **Ajouter**

![mobile2](../images/ajouter.png)

Voici les paramètres à renseigner :

-   **Nom de votre WES** : Nom du wes
-   **Activer** : Activation de le wes
-   **IP du WES** : renseigner l'ip d'accés au WES
-   **Dossier du WES** : il faut mettre "general" si c'est une configuration du wes.
-   **Port du WES** : laisser vide si vous n'avais pas changer celui-ci dans la configuration du Wes.
-   **Compte du WES** : mettre le login du compte Wes (a l'origine c'est "Admin").
-   **Password du WES** : mettre le mot de passe du compte Wes (a l'origine c'est "wes").

> **Tip**
>
> Si vous voulez changer ses information il faut d'abord ce rendre sur l'interface du WES.

![mobile3](../images/wesGlobal.png)

Après avoir sauvegardé, vous obtiendrez une multitude de module il vous suffit alors d'activer ceux que vous souhaitez.

![mobile4](../images/wesGlobalView.png)

> **Tip**
>
> les modules grisé ne sont pas surveillé par le plugin il faut bien les activer pour cela.

# Configuration des relais

Après l’initialisation du Plugin Wes, vous pouvez cliquer sur la clé voir ci-dessous.

![mobile5](../images/wesGlobalRelais.png)

En cliquant sur cette clé une modal s'affiche selectionner les relais et bouton souhaiter et valider. ce qui entraine un scnéario sur le wes pour avoir en temps réél les retour d'information des boutons et des relais (pour les autre retour d'information il faut aller dans les paramettre generaux du plugin.)

![mobile6](../images/wesGlobalView.png)
