# Sample Markdown Document

This is a simple example of a Markdown file with a table and a Mermaid flowchart.

---

## 📊 Sample Table

| ID | Name        | Role            | Status   |
|----|-------------|-----------------|----------|
| 1  | Ahmad       | Chief Specialist| Active   |
| 2  | Sara        | Developer       | Active   |
| 3  | John        | Designer        | Inactive |

---

## 🔄 Process Flow (Mermaid)

```mermaid
flowchart TD
    A[Start] --> B{Is User Logged In?}
    B -- Yes --> C[Show Dashboard]
    B -- No --> D[Redirect to Login]
    D --> E[User Logs In]
    E --> C
    C --> F[Perform Actions]
    F --> G[Logout]
    G --> H[End]
```