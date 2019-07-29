# Magento 2 Product Questions
Magento 2 Product Questions extension is an awesome module for Magento 2. 
It allows Customer or Guest to submit questions and answers for the product on the product detail page.

## Highlight features for for Product Questions

### Backend

#### Configuration
- Allowing setting Guests and Customers to Write Question or Answer.
- Allowing setting Questions per Page on List Default Value
- Allowing setting Auto approval new question and answer.
- Allowing setting the Question Rules Page.
#### Management
- Allowing administrator to check, approve and write answer for the question.
- List of Questions and Answers for the products.
- CRUD questions and answers

### Frontend
- Displaying the form allow to submit a question and an answer on the product detail page.
- Displaying the list of questions and answers for the product on the product detail page
- Displaying the list of questions and answer on my product questions page.

## Introduction installation:

### 1 - Installation Magento 2 Product Questions extension
#### Manual Installation
Install Product Questions extension for Magento2
 * Download the extension
 * Unzip the file
 * Create a folder {Magento root}/app/code/Ecomteck/ProductQuestions
 * Copy the content from the unzip folder


##### Using Composer

```
composer require ecomteck/module-product-questions

```

### 2 - Enable And Install the extension
 * php bin/magento module:enable Ecomteck_ProductQuestions
 * php bin/magento setup:upgrade
 * php bin/magento cache:clean
 * php bin/magento setup:static-content:deploy

### 3 - See results
- Going to the product detail page on frontend, at product tab you can see a tab Question.
- Administrator go to Marketing ->Product Questions to see the Questions and Answers management
- Stores -> Configuration -> Catalog -> Product Questions to see the configurations of extension.

## ScreenShots
update soon.
