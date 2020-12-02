# InstaClone

<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li><a href="#demo">Demo</a></li>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgements">Acknowledgements</a></li>
  </ol>
</details>




<!-- Demo -->
## Demo

To see the demo, visit this link : https://www.youtube.com/watch?v=lcjqbgalgwI

<a href="https://www.youtube.com/watch?v=lcjqbgalgwI">![Instaclone_Moment(4)](https://user-images.githubusercontent.com/68733361/100875879-e2d8b300-34a6-11eb-92b1-9f04219a5d0b.jpg)</a>



<!-- ABOUT THE PROJECT -->
## About The Project

This is a clone of instagram that includes stories, realtime chat & notifications among other features. It is my second project after only a couple of weeks of being introduced to Laravel and Vue js, the focus was on being able to make things work rather than doing it in the most optimized way. 

This project has been done in pair programming with [Diae Louali](https://github.com/Diae-Louali). Neither of us had any experience with frameworks prior to this project.

### Built With

* [Vue js](https://vuejs.org/)
* [Laravel](https://laravel.com)


<!-- GETTING STARTED -->
## Getting Started

In order to make the project run on your computer, follow these steps :
NOTICE : you can global search for " ****REMOVED**** " to easily locate all the information that needs to be replaced in the project.

### Prerequisites

* [Pusher](https://pusher.com/) account

* [Cloudinary](https://cloudinary.com/) account

* [Unsplash](https://unsplash.com/developers) account


### Installation

* Clone the repo and cd into the project's folder

   ```sh
   git clone https://github.com/AdamOB1290/instaCLone.git
   ```
   ```sh
   cd instaCLone
   ```
   
* Duplicate your ".env.example" file and rename it to ".env"

* In your terminal, execute :

   ```sh
   composer install
   ```
   ```sh
   npm install
   ```
   ```sh
   php artisan key:generate
   ```
   
* In your ".env" file make sure your have the following :

   ```sh
   DB_DATABASE=instaclone
   ```
   ```sh
   BROADCAST_DRIVER=pusher
   ```
   
* In [Pusher](https://pusher.com/), under Channels, create your app
   
* Copy your [Pusher](https://pusher.com/) keys located in App Keys and paste them in your ".env" file like so :

    ```sh
    - PUSHER_APP_ID = app_id goes here 
    - PUSHER_APP_KEY = key goes here 
	- PUSHER_APP_SECRET = secret goes here 
	- PUSHER_APP_CLUSTER = cluster goes here 
    ```

* Add at the bottom of your .env file : 
    ```sh
    - BASE_URL=https://localhost:8000
    ```
* In [Unsplash](https://unsplash.com/developers), register your app and copy the api keys

* Paste the keys in "database/factories/PostFactory.php" and "database/factories/UserFactory.php" like so :

    ```sh
    'applicationId'	=> 'applicationId goes here',
    'secret'	=> 'secret goes here',
    ```
    
* In [Cloudinary](https://cloudinary.com/), create an uploadPreset and enable cropping

* Copy your cloudName and uploadPreset in "ressource/js/app.js", "ressource/js/components/EditProfile.vue" & "ressource/js/components/Feed.vue" like so :
    
    ```sh
    cloudName: "cloudName goes here",
    uploadPreset: "uploadPreset goes here",
    ```
    
* Create your SQL database under the name " instaclone "

* In your terminal, execute the following : 

    ```sh
    php artisan migrate
    ```
    ```sh
    php artisan db:seed
    ```



<!-- CONTACT -->
## Contact

Adam LOUALI - [LinkedIn](https://www.linkedin.com/in/adam-louali/) - loualiadam0@gmail.com

Project Link: [https://github.com/AdamOB1290/instaCLone](https://github.com/AdamOB1290/instaCLone)



<!-- ACKNOWLEDGEMENTS -->
## Acknowledgements
* [Pusher](https://pusher.com/)
* [Cloudinary](https://cloudinary.com/)
* [Bootstrap Vue](https://bootstrap-vue.org/)
* [Tailwind](https://tailwindcss.com/)
* [Animate.css](https://animate.style/)
* [Vuex](https://vuex.vuejs.org/)
* [Slick Carousel](https://kenwheeler.github.io/slick/)
* [Glide js](https://glidejs.com/)
* [Unsplash](https://unsplash.com/developers)
* [Faker](https://github.com/fzaninotto/Faker)

