# Mobile Information Systems Course Project - Backend & Database

## Project Overview

This repository contains the backend and database components of a mobile application project for the "Mobile Information Systems" course at Wrocław University of Science and Technology.
The project aims to develop a mobile application for real-time music recognition.
This part of the project focuses on the server-side functionality, including user management, music recognition history, and interactions with the music database.

## Technologies

- **Backend Framework**: PHP Symfony
- **Database**: MySQL
- **External API for Music Recognition**: [RapidAPI - Music Identify](https://rapidapi.com/eipiai-eipiai-default/api/music-identify)


## Features

- **User Account Management**:
    - User registration
    - Simple login process
- **Music Recognition**:
    - Searching for a song in real-time using an external API
- **Favorites and History**:
    - Adding songs to favorites
    - Viewing recognition history
- **Social Features**:
    - Sharing recognized songs with others

## Database Schema

Refer to the Entity Relationship Diagram (ERD) included in the project documentation to understand the database schema used for managing users, songs, and their interactions.

## API Endpoints

- **Registration**: Endpoint for user registration
- **Login**: Endpoint for user login
- **Favorite Track CRUD**: Endpoints for creating, reading, updating, and deleting favorite tracks
- **Sharing Songs**: Endpoint for sharing songs with others
- **Recognition History CRUD**: Endpoints for managing the recognition history
