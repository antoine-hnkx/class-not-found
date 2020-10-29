-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 07 mai 2019 à 09:37
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

--
-- Données pour `project`
--

-- --------------------------------------------------------

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(5, '3D Graphics'),
(3, 'AI'),
(2, 'Algorithms'),
(4, 'Big Data'),
(1, 'General'),
(6, 'Web');

-- --------------------------------------------------------

--
-- Déchargement des données de la table `members`
--

INSERT INTO `members` (`member_id`, `login`, `lastname`, `firstname`, `mail`, `password`, `admin`, `suspended`) VALUES
(1, 'yusuf.yilmaz', 'Yilmaz', 'Yusuf', 'yusuf.yilmaz@student.vinci.be', '$2y$10$Apvf/0QLDpIKl3CmZ3ysJeh2xKtwQ7ITvJiuzmY1tLL6TYcjyMjde', 0, 0),
(2, 'antoine.honinckx', 'Honinckx', 'Antoine', 'antoine.honinckx@student.vinci.be', '$2y$10$Apvf/0QLDpIKl3CmZ3ysJeh2xKtwQ7ITvJiuzmY1tLL6TYcjyMjde', 1, 0),
(3, 'marc.hilbert', 'Hilbert', 'Marc', 'marc.hilbert@gmail.com', '$2y$10$Apvf/0QLDpIKl3CmZ3ysJeh2xKtwQ7ITvJiuzmY1tLL6TYcjyMjde', 0, 1),
(4, 'paul.pauly', 'Pauly', 'Paul', 'paul.pauly@gmail.com', '$2y$10$Apvf/0QLDpIKl3CmZ3ysJeh2xKtwQ7ITvJiuzmY1tLL6TYcjyMjde', 0, 0);



-- --------------------------------------------------------

--
-- Déchargement des données de la table `questions`
--


INSERT INTO `questions` (`question_id`, `author_id`, `category_id`, `best_answer_id`, `title`, `subject`, `state`, `publication_date`) VALUES
(1, 2, 2, NULL, 'How to do a foreach in Java ?', 'Hello,\r\n\r\nHow to do a foreach in Java ?\r\n\r\nThx', 'duplicated', '2019-03-05'),
(2, 1, 5, NULL, 'Blender help!', 'I was wondering how to use blender, do you have tips ?\r\n\r\nThank you :)', 'duplicated', '2019-03-06'),
(3, 2, 3, NULL, 'AI career', 'What kind of studies do you have to do in order to have a career in AI ?', NULL, '2019-03-21'),
(4, 1, 1, NULL, 'How to make cookies ?', 'Do you guys have any recipe for cookies ?', NULL, '2019-03-22'),
(5, 2, 6, NULL, 'Where to learn PHP ?', 'What sites can I use to learn PHP ?', NULL, '2019-03-23');

-- --------------------------------------------------------

--
-- Déchargement des données de la table `answers`
--

INSERT INTO `answers` (`answer_id`, `author_id`, `question_id`, `subject`, `publication_date`) VALUES
(1, 2, 1, 'You can find documentation on the javadoc website :)', '2019-03-05'),
(2, 1, 1, 'Okay thank you', '2019-03-06'),
(3, 2, 1, 'Need anything else ?', '2019-03-07'),
(4, 1, 1, 'Can you provide the link plz ?', '2019-03-07'),
(5, 2, 1, 'javadoc.com/foreach', '2019-03-07'),
(6, 2, 2, 'What version are you using ?', '2019-03-06'),
(7, 1, 2, 'Version 1.6', '2019-03-12'),
(8, 2, 2, 'You can check out my YT channel I have a series of videos dedicated to Blender', '2019-03-13'),
(9, 1, 2, 'Thanks I\'ll check it out ! :)', '2019-03-13'),
(10, 2, 2, 'You\'re welcome !', '2019-03-13'),
(11, 2, 3, 'Wich country do you live in ?', '2019-03-21'),
(12, 1, 3, 'In Belgium', '2019-03-22'),
(13, 2, 3, 'Well do you have a bachelor in computer science ?', '2019-03-22'),
(14, 1, 3, 'Yes I do', '2019-03-23'),
(15, 2, 3, 'You could go for a master in AI if any school in belgium are proposing one, if not then you might need to change country to pursue your studies.', '2019-03-24'),
(16, 2, 4, 'Are you lactose intolerant ? ', '2019-03-22'),
(17, 1, 4, 'No I\'m not', '2019-03-22'),
(18, 2, 4, 'Then you can use this recipe:\r\n- milk\r\n- chocolate\r\n- eggs\r\n- flour', '2019-03-23'),
(19, 1, 4, 'Ok thank you', '2019-03-23'),
(20, 2, 4, 'You are welcome', '2019-03-23'),
(21, 2, 5, 'PS: I\'m not an IT student so I need to learn from scratch', '2019-03-23'),
(22, 1, 5, 'There is plenty of documentation on the official PHP.net website', '2019-03-23'),
(23, 2, 5, 'Any other recommendations ?', '2019-03-23'),
(24, 1, 5, 'You can check this guy on Youtube: [link]', '2019-03-24'),
(25, 2, 5, 'Great thanks', '2019-03-24');



-- --------------------------------------------------------

--
-- Déchargement des données de la table `votes`
--

INSERT INTO `votes` (`member_id`, `answer_id`, `liked`) VALUES
(1, 1, 1),
(2, 12, 0);
