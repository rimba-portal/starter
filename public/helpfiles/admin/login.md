# Ideal MFA Login Duration

There isn't a single ideal MFA login duration—it depends on the risk level of the application.

## Common Recommendations

| Application Type | MFA Re-prompt Frequency |
|-----------------|-------------------------|
| Low-risk internal apps | 30–90 days |
| Corporate productivity apps (Microsoft 365, Teams, SharePoint) | 7–30 days on trusted devices |
| Sensitive business systems (HR, Finance, ERP) | Every 8–24 hours |
| Highly sensitive/admin access | Every login or every few hours |
| Privileged administrator accounts | Every login + phishing-resistant MFA |

Microsoft recommends balancing security and usability rather than forcing frequent MFA prompts, because excessive prompts can lead to MFA fatigue and poorer security behavior. Microsoft Entra's default sign-in frequency uses a 90-day rolling window, with Conditional Access policies used to require reauthentication when risk increases. [1](https://learn.microsoft.com/en-us/entra/identity/authentication/concepts-azure-multi-factor-authentication-prompts-session-lifetime)

## Recommended Enterprise Configuration

- Trusted device: MFA challenge every **7–30 days**
- Untrusted device: MFA challenge every **login**
- New location/device: MFA challenge every **login**
- Administrator accounts: MFA challenge every **login**
- High-risk transactions: Step-up MFA as required

Microsoft specifically recommends minimizing MFA prompts on known devices and notes that remembered MFA periods of less than 30 days may unnecessarily impact user productivity. [2](https://learn.microsoft.com/en-us/entra/identity/monitoring-health/recommendation-mfa-from-known-devices)

## Login Experience KPI

| Metric | Target |
|----------|---------|
| Excellent | < 15 seconds |
| Good | 15–30 seconds |
| Acceptable | 30–60 seconds |
| Poor | > 60 seconds |

For most corporate users, the complete authentication flow (credentials + MFA verification) should ideally take **less than 30 seconds** from sign-in initiation to access granted.