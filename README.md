# 🌐 Site du Lycée Louis Vincent

![Bannière du Lycée Louis Vincent](https://www.lycee-louis-vincent.fr/images/bannerfans_11654068.png)

Projet officiel de refonte du site internet du **Lycée Louis Vincent**.  
Développé par des élèves de Terminale NSI, ce site vise à moderniser l'expérience utilisateur, centraliser les informations et offrir un espace numérique clair et accessible.

---

## 👨‍💻 Équipe de développement

- **Florentin Fouligny** – Developer 
- **Gabin Decaillot** – Developer  
- **Erwan Trevien** – Developer  

---

## 📈 Statistiques du dépôt

- **Commits récents** : Ajout du module *Agenda* et refonte de la gestion des pages  
- **Activité** : Développement actif depuis juin 2025  
- **Langages principaux** : PHP (Laravel), Blade, TailwindCSS, MySQL  
---

## 📅 Roadmap & Phases du projet

- [x] **Phase 1 : Conceptions des maquettes**  
- [x] **Phase 2 : Validation**  
- [x] **Phase 3 : Cahier des charges**  
- [x] **Phase 4 : Installation des dépendances**  
- [🚧] **Phase 5 : Développement concret (en cours)**  
- [ ] **Phase 6 : Tests & validation**  
- [ ] **Phase 7 : Déploiement**

---

## ⚙️ Technologies utilisées

- **Backend** : Laravel (PHP 8)  
- **Frontend** : HTML5 + TailwindCSS + POSTCSS + VITE
- **Base de données** : MySQL  
- **Versionning** : Git & GitHub  

---

## 🚀 Installation locale

1. **Cloner le projet**
   ```bash
   git clone https://github.com/cauctoflo/LouisVincentSite.git
   cd LouisVincentSite
   ```

2. **Installer les dépendances Laravel**
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configurer la base de données**  
   Modifier `.env` avec vos identifiants MySQL puis exécuter :
   ```bash
   php artisan migrate
   ```

4. **Installer et compiler le front**
   ```bash
   npm install
   npm run dev
   ```

5. **Lancer le serveur**
   ```bash
   php artisan serve
   ```

Le site est alors accessible sur **http://127.0.0.1:8000**

---

## 🤝 Contribution

1. Forkez le dépôt  
2. Créez une branche (`feature/ma-fonctionnalite`)  
3. Faites vos commits (`git commit -m 'Ajout de X'`)  
4. Push (`git push origin feature/ma-fonctionnalite`)  
5. Ouvrez une **Pull Request**

---

## 📌 Suivi & contacts

- Site officiel : [lycee-louis-vincent.fr](https://www.lycee-louis-vincent.fr/)  
- Dépôt GitHub : [LouisVincentSite](https://github.com/cauctoflo/LouisVincentSite)   

---
