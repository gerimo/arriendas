
DROP TRIGGER IF EXISTS  `oportunityQueue`;
DROP TRIGGER IF EXISTS  `oportunityReserveQueue`;

DELIMITER $$

CREATE TRIGGER `oportunityQueue` AFTER UPDATE ON `Transaction` 
FOR EACH ROW BEGIN IF NEW.completed AND NEW.impulsive THEN 
    INSERT IGNORE INTO OportunityQueue( reserve_id ) VALUES (NEW.reserve_id);
END IF;
END$$


CREATE TRIGGER `oportunityReserveQueue` AFTER UPDATE ON  `Reserve` 
FOR EACH ROW BEGIN IF NEW.confirmed AND NEW.impulsive THEN 
    UPDATE OportunityQueue SET is_active = FALSE WHERE reserve_id = NEW.id;
END IF;
END$$

DELIMITER ;