DROP TABLE IF EXISTS rss_item;

CREATE TABLE rss_item (
    id           int(11)   NOT NULL AUTO_INCREMENT,
    guid         text      DEFAULT NULL,
    pub_date     datetime  NOT NULL,
    title        text      NOT NULL,
    description  text      NOT NULL,
    author       text      NOT NULL,
    link         text      DEFAULT NULL,
    PRIMARY KEY (id)
)