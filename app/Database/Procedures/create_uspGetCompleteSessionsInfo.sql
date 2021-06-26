CREATE DEFINER=`root`@`localhost` PROCEDURE `uspGetCompleteSessionsInfo`(
	IN `p_session_uid` CHAR(36),
	IN `p_attendant_uid` CHAR(36),
	IN `p_attendee_uid` CHAR(36),
	IN `p_session_status` VARCHAR(50),
	IN `p_limit` INT,
	IN `p_offset` INT
)
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
-- uspGetCompleteSessionsInfo
--
-- Get all information about sessions 
--
-- p_session_uid CHAR(36)
-- p_attendant_uid CHAR(36)
-- p_attendee_uid  CHAR(36)
-- p_session_status VARCHAR(50)
-- p_limit INT
-- p_offset

-- session
-- CALL uspGetCompleteSessionsInfo( '1ebd12b4-9829-6280-8fc7-f48e38e2d161', NULL, NULL, NULL, 1, 0 )

-- attendant
-- CALL uspGetCompleteSessionsInfo( NULL, '1ebd12b4-5739-6ebe-8bb4-f48e38e2d161', NULL, NULL, 1, 0 )

-- attendee
-- CALL uspGetCompleteSessionsInfo( NULL, NULL, '1ebd12b4-6000-6cdc-93ed-f48e38e2d161', NULL, 1, 0 )

-- status
-- CALL uspGetCompleteSessionsInfo( NULL, NULL, NULL, 'open' , 100, 0 )

SELECT
  s.session_uid,
  s.attendee_uid,
  s.attendant_uid,
  s.session_status,
  c.user_name as attendee_name,
  COALESCE(c.user_avatar,c.google_avatar,c.facebook_avatar) as attendee_avatar,
  a.user_name AS attendant_name,
  a.user_avatar AS attendant_avatar,
  m.message AS last_message,
  m.created_at AS last_message_time,
  if(m.sender_uid IS NULL, NULL, if(m.sender_uid = s.attendant_uid, "attendant", "attendee")) AS last_message_sender,
  (SELECT COUNT(*) FROM chat_message m2 WHERE m2.sender_uid = s.attendant_uid ) attendant_message_count,
  (SELECT COUNT(*) FROM chat_message m2 WHERE m2.sender_uid = s.attendee_uid ) attendee_message_count,
  (SELECT COUNT(*) FROM chat_message m2 WHERE m2.sender_uid = s.attendee_uid AND m2.sync_status <> "read" ) attendant_unread_count,
  (SELECT COUNT(*) FROM chat_message m2 WHERE m2.sender_uid = s.attendant_uid AND m2.sync_status <> "read" ) attendee_unread_count
FROM 
  chat_session s
  LEFT OUTER JOIN chat_message m on s.session_uid = m.session_uid  
    AND   m.id = (SELECT max(m2.id) FROM chat_message m2 WHERE m2.session_uid = s.session_uid) 
  LEFT OUTER JOIN chat_user c ON c.chat_user_uid = s.attendee_uid
  LEFT OUTER JOIN chat_user a ON a.chat_user_uid = s.attendant_uid
WHERE 
  (p_session_uid is null or s.session_uid = p_session_uid)
 
AND   (p_attendant_uid is null or s.attendant_uid = p_attendant_uid)
AND   (p_attendee_uid is null or s.attendee_uid = p_attendee_uid)
AND   (p_session_status is null or s.session_status = p_session_status)

order by s.created_at ASC
limit p_offset, p_limit