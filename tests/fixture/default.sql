
-----------------------------------------------------------------------
-- projects
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [projects];

CREATE TABLE [projects]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(255) NOT NULL,
    [repository] VARCHAR(255) NOT NULL,
    [created_at] TIMESTAMP,
    [updated_at] TIMESTAMP,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- roles
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [roles];

CREATE TABLE [roles]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] VARCHAR(255) NOT NULL,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- users
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [users];

CREATE TABLE [users]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [username] VARCHAR(255) NOT NULL,
    [password] VARCHAR(255) NOT NULL,
    [created_at] TIMESTAMP,
    [updated_at] TIMESTAMP,
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- users_auth_tokens
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [users_auth_tokens];

CREATE TABLE [users_auth_tokens]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [user_id] INTEGER,
    [token] VARCHAR(255) NOT NULL,
    [created_at] TIMESTAMP,
    [updated_at] TIMESTAMP,
    UNIQUE ([id]),
    FOREIGN KEY ([user_id]) REFERENCES [users] ([id])
);

CREATE INDEX [users_auth_tokens_i_6ca017] ON [users_auth_tokens] ([user_id]);

-----------------------------------------------------------------------
-- users_roles
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [users_roles];

CREATE TABLE [users_roles]
(
    [user_id] INTEGER NOT NULL,
    [role_id] INTEGER NOT NULL,
    PRIMARY KEY ([user_id],[role_id]),
    UNIQUE ([user_id],[role_id]),
    FOREIGN KEY ([user_id]) REFERENCES [users] ([id]),
    FOREIGN KEY ([role_id]) REFERENCES [roles] ([id])
);

-----------------------------------------------------------------------
-- users_privileges
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [users_privileges];

CREATE TABLE [users_privileges]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [user_id] INTEGER,
    [name] VARCHAR(255) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([user_id]) REFERENCES [users] ([id])
        ON DELETE CASCADE
);
