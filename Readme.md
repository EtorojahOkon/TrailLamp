# Traillamp
A lightweight, easy to use, MVC Framework for Php

## What's New in V1.1.0
- Better Url routing
- Support for additional request types
- Better error handling
- Robust Templating Engine
- Middlewares
- Additional Console Commands
- Support for all web servers
- Additional utilities
- Migration Schemas
- Flexible Models

## Documentation

### Routing


{% for user in users %}
        {{user.name}}  
    {% else %}
        No Users found  
   {% endfor %}

   try {
            $sql = "SELECT * FROM tasty";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
            
        } 
        catch (PDOException $error) {
            echo "Error in query:\n".$error->getMessage();
        }