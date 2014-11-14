
DROP TRIGGER IF EXISTS  `oportunityQueue`;
DROP TRIGGER IF EXISTS  `oportunityReserveQueue`;

DELIMITER $$

CREATE TRIGGER `oportunityQueue` BEFORE UPDATE ON `Transaction` 
FOR EACH ROW BEGIN IF NEW.completed AND OLD.completed <> NEW.completed AND NEW.impulsive THEN 
    INSERT IGNORE INTO OportunityQueue( reserve_id ) VALUES (NEW.reserve_id);
END IF;
END$$


CREATE TRIGGER `oportunityReserveQueue` BEFORE UPDATE ON  `Reserve` 
FOR EACH ROW BEGIN 
    IF NEW.confirmed AND OLD.confirmed <> NEW.confirmed AND NEW.impulsive AND NEW.reserva_original IS NULL THEN 
        UPDATE OportunityQueue SET is_active = FALSE WHERE reserve_id = NEW.id;
    END IF;
END$$

DELIMITER ;