<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\SeedsServiceContent;
use Illuminate\Database\Seeder;

class InfrastructureSurveillanceContentSeeder extends Seeder
{
    use SeedsServiceContent;

    public function run(): void
    {
        $capability = $this->seedCapability([
            'slug' => 'infrastructure-surveillance',
            'category' => 'infrastructure_surveillance',
            'title' => 'Infrastructure & Surveillance',
            'short_description' => 'Security, networking, servers, storage, cabling, workstations, and AMC support for always-on business operations.',
            'icon' => 'capabilities/surveillance-and-infrastructure-connection-gfdn3lr1.png',
            'sort_order' => 5,
        ]);

        foreach ($this->services() as $service) {
            $this->seedService($capability, array_merge($service, ['category' => 'infrastructure_surveillance']));
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function services(): array
    {
        return [
            [
                'slug' => 'cctv-security-solutions',
                'title' => 'CCTV & Security Solutions',
                'sort_order' => 1,
                'short_description' => 'CCTV and security solutions for offices, factories, societies, schools, warehouses, and commercial premises.',
                'seo_title' => 'CCTV & Security Solutions in Navi Mumbai | Tectignis',
                'seo_description' => 'CCTV and security solutions in Navi Mumbai for cameras, NVR, remote viewing, video analytics, alarms, monitoring, installation, and AMC support.',
                'heading' => 'CCTV and Security Solutions for Safer Premises',
                'intro' => 'Protect people, property, and operations with surveillance systems planned around your site layout. We design, install, and support CCTV and security solutions for offices, factories, societies, schools, and retail spaces.',
                'bullets' => [
                    'Camera planning for entry, perimeter, floor, and critical zones',
                    'NVR, remote viewing, backup, and monitoring setup',
                    'Maintenance support to keep coverage reliable',
                ],
                'sub_services' => [
                    ['title' => 'CCTV Site Survey', 'description' => 'Assess entrances, blind spots, lighting, cabling routes, storage needs, and monitoring points.'],
                    ['title' => 'IP and Analog Camera Setup', 'description' => 'Install dome, bullet, PTZ, and special-purpose cameras based on site requirements.'],
                    ['title' => 'NVR and Storage Planning', 'description' => 'Configure recording capacity, retention periods, backup, and viewing permissions.'],
                    ['title' => 'Remote Viewing', 'description' => 'Enable secure mobile and desktop access for authorized owners and security teams.'],
                    ['title' => 'Video Analytics', 'description' => 'Add motion, intrusion, line crossing, face, people, or vehicle detection where useful.'],
                    ['title' => 'CCTV AMC Support', 'description' => 'Maintain cameras, cables, power, recording health, cleaning, and fault resolution.'],
                ],
                'why_points' => [
                    'Camera placement planned from real site movement and risk points',
                    'Reliable brands selected for lighting, distance, storage, and budget needs',
                    'Secure remote access setup with clear user permissions',
                    'Recording and retention planning that matches operational requirements',
                    'On-site support for installation, troubleshooting, upgrades, and AMC',
                ],
                'faqs' => [
                    ['question' => 'How many CCTV cameras does my site need?', 'answer' => 'We decide after a site survey that checks entry points, blind spots, lighting, area size, and monitoring goals.'],
                    ['question' => 'Can CCTV be viewed remotely?', 'answer' => 'Yes. We can configure secure mobile and desktop viewing for authorized users.'],
                    ['question' => 'Do you provide storage planning?', 'answer' => 'Yes. We calculate recording retention based on camera count, resolution, frame rate, and storage capacity.'],
                    ['question' => 'Can you maintain existing CCTV systems?', 'answer' => 'Yes. We can audit, repair, clean, reconfigure, and maintain existing camera and recording setups.'],
                ],
                'tech_stacks' => ['Hikvision', 'Dahua', 'CP Plus', 'Axis', 'Bosch', 'Honeywell'],
                'industries' => ['Corporate Offices', 'Manufacturing', 'Retail', 'Education', 'Hospitality', 'Healthcare'],
                'cta_heading' => 'Ready to Improve Site Security Coverage?',
            ],
            [
                'slug' => 'access-control-systems',
                'title' => 'Access Control Systems',
                'sort_order' => 2,
                'short_description' => 'Access control systems for biometric entry, RFID cards, door locks, attendance, visitor access, and multi-site control.',
                'seo_title' => 'Access Control Systems in Navi Mumbai | Tectignis',
                'seo_description' => 'Access control systems in Navi Mumbai for biometric, RFID, door locks, attendance, visitor access, multi-site management, and security integration.',
                'heading' => 'Access Control Systems for Safer Entry Management',
                'intro' => 'Control who enters your premises, when they enter, and which areas they can access. We install and configure access control systems for offices, factories, schools, hospitals, and commercial buildings.',
                'bullets' => [
                    'Biometric, RFID, card, and PIN-based access',
                    'Door locks, controllers, attendance, and visitor flows',
                    'Central permissions for users, zones, and branches',
                ],
                'sub_services' => [
                    ['title' => 'Access Control Survey', 'description' => 'Review doors, user groups, restricted zones, wiring, locks, and emergency requirements.'],
                    ['title' => 'Biometric Entry Setup', 'description' => 'Configure fingerprint, face, or palm-based access for staff and authorized users.'],
                    ['title' => 'RFID and Card Systems', 'description' => 'Issue cards, set access rules, and manage lost, blocked, or temporary credentials.'],
                    ['title' => 'Door Lock Integration', 'description' => 'Install electromagnetic locks, drop bolts, exit buttons, sensors, and controllers.'],
                    ['title' => 'Attendance Integration', 'description' => 'Sync access logs with attendance, shift, and HR reporting workflows.'],
                    ['title' => 'Multi-Site Management', 'description' => 'Control users, doors, schedules, and logs across multiple branches from one system.'],
                ],
                'why_points' => [
                    'Access plans designed around zones, shifts, departments, and risk levels',
                    'Hardware selection based on door type, traffic volume, and security needs',
                    'Clear permissions for staff, visitors, vendors, and administrators',
                    'Integration options for HRMS, visitor management, CCTV, and alarms',
                    'Support for installation, training, troubleshooting, and maintenance',
                ],
                'faqs' => [
                    ['question' => 'Can access control connect with attendance?', 'answer' => 'Yes. Entry and exit logs can be used for attendance, shift reports, and HRMS integration.'],
                    ['question' => 'Can visitors get temporary access?', 'answer' => 'Yes. Visitor access can be time-bound and linked with approvals or reception workflows.'],
                    ['question' => 'Can different staff access different areas?', 'answer' => 'Yes. We can configure permissions by user, department, door, zone, schedule, or branch.'],
                    ['question' => 'Can access control work across multiple locations?', 'answer' => 'Yes. Multi-site systems can manage users, doors, logs, and permissions centrally.'],
                ],
                'tech_stacks' => ['ZKTeco', 'Matrix', 'eSSL', 'Honeywell', 'Bosch', 'Hikvision'],
                'industries' => ['Corporate Offices', 'Manufacturing', 'Healthcare', 'Education', 'Finance & Banking'],
                'cta_heading' => 'Ready to Control Entry Across Your Premises?',
            ],
            [
                'slug' => 'networking-solutions',
                'title' => 'Networking Solutions',
                'sort_order' => 3,
                'short_description' => 'Networking solutions for LAN, WAN, Wi-Fi, switches, routers, firewalls, VPN, monitoring, and branch connectivity.',
                'seo_title' => 'Networking Solutions in Navi Mumbai | Tectignis',
                'seo_description' => 'Networking solutions in Navi Mumbai for LAN, Wi-Fi, WAN, switches, routers, firewalls, VPN, monitoring, setup, troubleshooting, and support.',
                'heading' => 'Networking Solutions That Keep Teams Connected',
                'intro' => 'Build a network that supports your users, applications, devices, and future growth. We plan, deploy, secure, and support wired and wireless networks for offices, factories, campuses, and branches.',
                'bullets' => [
                    'LAN, WAN, Wi-Fi, firewall, and VPN planning',
                    'Switching, routing, segmentation, and monitoring',
                    'Reliable connectivity for users, servers, CCTV, and cloud apps',
                ],
                'sub_services' => [
                    ['title' => 'Network Design', 'description' => 'Plan topology, IP structure, VLANs, bandwidth, cabling, switching, routing, and security.'],
                    ['title' => 'LAN and Switch Setup', 'description' => 'Configure managed switches, uplinks, VLANs, PoE, redundancy, and port security.'],
                    ['title' => 'Business Wi-Fi', 'description' => 'Deploy access points, roaming, guest networks, coverage planning, and authentication.'],
                    ['title' => 'WAN and Branch Connectivity', 'description' => 'Connect branches, warehouses, data centers, and cloud systems using secure links.'],
                    ['title' => 'VPN and Remote Access', 'description' => 'Set up secure remote and site-to-site access for users, vendors, and locations.'],
                    ['title' => 'Network Monitoring', 'description' => 'Track uptime, device health, bandwidth, alerts, and recurring connectivity issues.'],
                ],
                'why_points' => [
                    'Network architecture planned for performance, coverage, security, and growth',
                    'Vendor experience across Cisco, Fortinet, Aruba, Ubiquiti, and common SMB gear',
                    'Segmentation options for users, guests, servers, CCTV, and IoT devices',
                    'Documentation that makes future troubleshooting and expansion easier',
                    'Support for new deployments, upgrades, audits, and ongoing maintenance',
                ],
                'faqs' => [
                    ['question' => 'Can you improve weak office Wi-Fi?', 'answer' => 'Yes. We survey coverage, interference, access point placement, roaming, bandwidth, and configuration issues.'],
                    ['question' => 'Can you connect multiple branches?', 'answer' => 'Yes. We can design secure branch connectivity using VPN, SD-WAN-style planning, or provider links.'],
                    ['question' => 'Can CCTV and office users be separated?', 'answer' => 'Yes. VLANs and firewall policies can separate CCTV, guest, staff, server, and management traffic.'],
                    ['question' => 'Do you monitor networks after setup?', 'answer' => 'Yes. We can provide monitoring, alerts, documentation, change support, and troubleshooting.'],
                ],
                'tech_stacks' => ['Cisco', 'Fortinet', 'Aruba', 'Ubiquiti', 'D-Link', 'TP-Link'],
                'industries' => ['Corporate Offices', 'Manufacturing', 'Education', 'Healthcare', 'Logistics'],
                'cta_heading' => 'Ready to Build a More Reliable Network?',
            ],
            [
                'slug' => 'server-management',
                'title' => 'Server Management',
                'sort_order' => 4,
                'short_description' => 'Server management for Windows, Linux, virtualization, monitoring, backups, security, patches, performance, and support.',
                'seo_title' => 'Server Management Services in Navi Mumbai | Tectignis',
                'seo_description' => 'Server management services in Navi Mumbai for Windows, Linux, VMware, monitoring, backups, security, patching, performance, and support.',
                'heading' => 'Server Management for Stable Business Systems',
                'intro' => 'Keep critical servers secure, updated, monitored, and recoverable. We manage physical, virtual, and cloud servers so applications, files, databases, and users stay productive.',
                'bullets' => [
                    'Windows, Linux, virtualization, and cloud server support',
                    'Monitoring, patching, backup, and security hardening',
                    'Performance tuning and incident response',
                ],
                'sub_services' => [
                    ['title' => 'Server Setup and Configuration', 'description' => 'Install and configure operating systems, roles, storage, users, and network settings.'],
                    ['title' => 'Virtualization Management', 'description' => 'Manage VMware or virtual hosts, VMs, resource allocation, snapshots, and capacity.'],
                    ['title' => 'Patch and Update Management', 'description' => 'Apply updates with scheduling, compatibility checks, and rollback awareness.'],
                    ['title' => 'Server Security Hardening', 'description' => 'Harden access, firewall rules, services, permissions, antivirus, and admin controls.'],
                    ['title' => 'Backup and Recovery', 'description' => 'Set up backup schedules, retention, recovery testing, and restore procedures.'],
                    ['title' => 'Server Monitoring', 'description' => 'Monitor uptime, disk, CPU, memory, services, logs, backups, and performance alerts.'],
                ],
                'why_points' => [
                    'Server care focused on uptime, recovery, security, and maintainability',
                    'Support for mixed Windows, Linux, VMware, and cloud environments',
                    'Backup planning that includes restore testing, not only scheduled jobs',
                    'Documentation for credentials, roles, services, dependencies, and procedures',
                    'Responsive support for performance issues, outages, updates, and expansion',
                ],
                'faqs' => [
                    ['question' => 'Can you manage both Windows and Linux servers?', 'answer' => 'Yes. We manage Windows, Linux, virtual, physical, and cloud server environments.'],
                    ['question' => 'Do you test server backups?', 'answer' => 'Yes. Backup health and restore testing can be included so recovery is proven before a failure.'],
                    ['question' => 'Can you take over existing servers?', 'answer' => 'Yes. We audit the current setup, document it, fix priority risks, and then move into ongoing management.'],
                    ['question' => 'Can you monitor server performance?', 'answer' => 'Yes. We can monitor uptime, disk usage, CPU, memory, services, logs, backups, and alerts.'],
                ],
                'tech_stacks' => ['Dell', 'HPE', 'Lenovo', 'Microsoft', 'Linux', 'VMware'],
                'industries' => ['Corporate Offices', 'Finance & Banking', 'Healthcare', 'Manufacturing', 'E-commerce'],
                'cta_heading' => 'Ready to Keep Your Servers Healthy?',
            ],
            [
                'slug' => 'storage-solutions',
                'title' => 'Storage Solutions',
                'sort_order' => 5,
                'short_description' => 'Storage solutions for NAS, SAN, backup, file sharing, archiving, disaster recovery, capacity planning, and data protection.',
                'seo_title' => 'Storage Solutions in Navi Mumbai | Tectignis',
                'seo_description' => 'Storage solutions in Navi Mumbai for NAS, SAN, backup, file sharing, archiving, disaster recovery, capacity planning, and data protection.',
                'heading' => 'Storage Solutions That Keep Business Data Available',
                'intro' => 'Store, share, protect, and recover business data with infrastructure sized for your workloads. We design storage solutions for file sharing, backup, surveillance, virtualization, applications, and long-term archives.',
                'bullets' => [
                    'NAS, SAN, backup, archive, and hybrid storage planning',
                    'Capacity, performance, redundancy, and retention design',
                    'Data protection and recovery workflows',
                ],
                'sub_services' => [
                    ['title' => 'Storage Assessment', 'description' => 'Review data volume, growth, users, workloads, retention, performance, and recovery needs.'],
                    ['title' => 'NAS File Sharing', 'description' => 'Deploy centralized file storage with permissions, sharing, snapshots, and remote access.'],
                    ['title' => 'SAN and Virtualization Storage', 'description' => 'Plan high-performance storage for servers, virtualization, databases, and applications.'],
                    ['title' => 'Backup Storage', 'description' => 'Set up backup targets, retention rules, encryption, restore testing, and offsite copies.'],
                    ['title' => 'Data Archiving', 'description' => 'Create organized storage for old records, compliance files, media, and low-access data.'],
                    ['title' => 'Storage Monitoring', 'description' => 'Track capacity, health, disks, alerts, replication, and backup success.'],
                ],
                'why_points' => [
                    'Storage sized for real workloads instead of only raw capacity',
                    'Vendor-neutral recommendations across NAS, SAN, backup, and cloud options',
                    'Access permissions and snapshots planned for safer file collaboration',
                    'Recovery planning for hardware failure, accidental deletion, and ransomware scenarios',
                    'Expansion strategy that avoids disruption as data grows',
                ],
                'faqs' => [
                    ['question' => 'Should we choose NAS or SAN?', 'answer' => 'NAS is often suited for shared files, while SAN is used for high-performance server and virtualization workloads. We assess your need first.'],
                    ['question' => 'Can storage be expanded later?', 'answer' => 'Yes. We plan capacity and expansion paths so growth does not require a complete redesign.'],
                    ['question' => 'Can you include offsite backup?', 'answer' => 'Yes. We can add local, offsite, cloud, or hybrid backup and recovery planning.'],
                    ['question' => 'Can old files be archived separately?', 'answer' => 'Yes. Archiving can reduce primary storage load while keeping records searchable and recoverable.'],
                ],
                'tech_stacks' => ['Dell EMC', 'NetApp', 'Synology', 'QNAP', 'Seagate', 'Western Digital'],
                'industries' => ['Corporate Offices', 'Healthcare', 'Finance & Banking', 'Manufacturing', 'Logistics'],
                'cta_heading' => 'Ready to Protect and Organize Your Business Data?',
            ],
            [
                'slug' => 'structured-cabling',
                'title' => 'Structured Cabling',
                'sort_order' => 6,
                'short_description' => 'Structured cabling services for copper, fiber, racks, patch panels, testing, labeling, documentation, and maintenance.',
                'seo_title' => 'Structured Cabling Services in Navi Mumbai | Tectignis',
                'seo_description' => 'Structured cabling services in Navi Mumbai for Cat6, fiber, racks, patch panels, testing, labeling, documentation, and maintenance.',
                'heading' => 'Structured Cabling for Clean, Reliable Connectivity',
                'intro' => 'Give your network a physical foundation that is organized, tested, and ready for growth. We plan and install structured cabling for offices, factories, campuses, server rooms, and data points.',
                'bullets' => [
                    'Cat6, Cat6A, fiber, rack, and patch panel installation',
                    'Neat routing, labeling, testing, and documentation',
                    'Cabling for data, voice, Wi-Fi, CCTV, and access control',
                ],
                'sub_services' => [
                    ['title' => 'Cabling Site Survey', 'description' => 'Map user points, racks, pathways, cable lengths, risers, and future expansion needs.'],
                    ['title' => 'Copper Network Cabling', 'description' => 'Install Cat6 or Cat6A cabling for desks, access points, cameras, and network devices.'],
                    ['title' => 'Fiber Optic Cabling', 'description' => 'Deploy fiber links for backbones, long distances, server rooms, and high-bandwidth areas.'],
                    ['title' => 'Rack and Patch Panel Setup', 'description' => 'Organize racks, patch panels, cable managers, switches, and power routing.'],
                    ['title' => 'Testing and Labeling', 'description' => 'Test links, label ports, prepare maps, and document cabling for maintenance.'],
                    ['title' => 'Cabling Maintenance', 'description' => 'Troubleshoot faults, clean up racks, add points, and support moves or expansions.'],
                ],
                'why_points' => [
                    'Cabling planned for current devices and future growth',
                    'Neat rack and cable management that simplifies troubleshooting',
                    'Testing and labeling included for professional handover',
                    'Support for network, Wi-Fi, CCTV, access control, and voice points',
                    'Experienced installation teams for offices, factories, schools, and multi-floor sites',
                ],
                'faqs' => [
                    ['question' => 'Do you provide cable testing?', 'answer' => 'Yes. We test links and provide labeling or documentation based on project scope.'],
                    ['question' => 'Can you install both copper and fiber?', 'answer' => 'Yes. We install copper cabling for local points and fiber for backbones or longer distances.'],
                    ['question' => 'Can you clean up an existing rack?', 'answer' => 'Yes. We can reorganize patch panels, switches, cables, labels, and power routing.'],
                    ['question' => 'Can cabling support CCTV and Wi-Fi?', 'answer' => 'Yes. We plan cable routes and PoE points for cameras, access points, phones, and network devices.'],
                ],
                'tech_stacks' => ['Panduit', 'CommScope', 'Belden', 'D-Link', 'Legrand', 'Schneider Electric'],
                'industries' => ['Corporate Offices', 'Manufacturing', 'Healthcare', 'Education', 'Hospitality'],
                'cta_heading' => 'Ready to Build a Cleaner Cabling Backbone?',
            ],
            [
                'slug' => 'workstation-solutions',
                'title' => 'Workstation Solutions',
                'sort_order' => 7,
                'short_description' => 'Workstation solutions for desktops, laptops, high-performance PCs, software setup, security, user migration, and support.',
                'seo_title' => 'Workstation Solutions in Navi Mumbai | Tectignis',
                'seo_description' => 'Workstation solutions in Navi Mumbai for desktops, laptops, high-performance PCs, software setup, endpoint security, user migration, and support.',
                'heading' => 'Workstation Solutions That Keep Every Desk Productive',
                'intro' => 'Equip teams with secure, reliable, and role-ready workstations. We help businesses choose, configure, deploy, migrate, secure, and support desktops, laptops, and high-performance PCs.',
                'bullets' => [
                    'Business desktops, laptops, and performance workstations',
                    'Software, security, user profiles, and device setup',
                    'Rollout, migration, troubleshooting, and maintenance support',
                ],
                'sub_services' => [
                    ['title' => 'Workstation Planning', 'description' => 'Match device specifications to roles, software, workload, budget, and growth needs.'],
                    ['title' => 'Desktop and Laptop Deployment', 'description' => 'Configure operating systems, users, drivers, updates, domain access, and applications.'],
                    ['title' => 'High-Performance Workstations', 'description' => 'Plan machines for design, CAD, engineering, analytics, media, and heavy workloads.'],
                    ['title' => 'Endpoint Security Setup', 'description' => 'Install antivirus, encryption, access controls, patching, and device protection policies.'],
                    ['title' => 'User Data Migration', 'description' => 'Move user files, settings, email profiles, and application data to new machines.'],
                    ['title' => 'Workstation Support', 'description' => 'Handle troubleshooting, upgrades, replacements, performance issues, and preventive maintenance.'],
                ],
                'why_points' => [
                    'Hardware recommendations based on real user workloads and software needs',
                    'Standardized setup that makes devices easier to support and secure',
                    'User migration planning to reduce downtime during replacements',
                    'Endpoint protection included as part of workstation readiness',
                    'Support for small teams, branch rollouts, and bulk deployments',
                ],
                'faqs' => [
                    ['question' => 'Can you supply and configure workstations?', 'answer' => 'Yes. We can help plan, procure, configure, secure, and deploy desktops or laptops.'],
                    ['question' => 'Can you set up high-performance PCs?', 'answer' => 'Yes. We can specify and configure machines for design, engineering, analytics, CAD, video, and demanding workloads.'],
                    ['question' => 'Can you migrate data from old systems?', 'answer' => 'Yes. We can migrate user files, profiles, email settings, and application data where supported.'],
                    ['question' => 'Do you provide ongoing support?', 'answer' => 'Yes. We support troubleshooting, updates, upgrades, endpoint security, and replacement planning.'],
                ],
                'tech_stacks' => ['Dell', 'HP', 'Lenovo', 'ASUS', 'Intel', 'AMD'],
                'industries' => ['Corporate Offices', 'Education', 'Manufacturing', 'Startups', 'E-commerce'],
                'cta_heading' => 'Ready to Equip Your Team With Better Workstations?',
            ],
            [
                'slug' => 'amc-services',
                'title' => 'AMC Services',
                'sort_order' => 8,
                'short_description' => 'AMC services for IT infrastructure, CCTV, networking, servers, workstations, preventive checks, SLAs, and support.',
                'seo_title' => 'IT AMC Services in Navi Mumbai | Tectignis',
                'seo_description' => 'IT AMC services in Navi Mumbai for computers, servers, networks, CCTV, access control, preventive maintenance, SLA support, and troubleshooting.',
                'heading' => 'AMC Services for Reliable IT and Security Operations',
                'intro' => 'Keep daily technology problems from interrupting business. Our AMC services provide preventive maintenance, responsive support, health checks, and clear ownership across IT, networking, surveillance, and workplace systems.',
                'bullets' => [
                    'Preventive maintenance for IT, network, server, and CCTV assets',
                    'Remote and on-site support with agreed response expectations',
                    'Health checks, reports, troubleshooting, and upgrade guidance',
                ],
                'sub_services' => [
                    ['title' => 'IT Infrastructure AMC', 'description' => 'Maintain desktops, laptops, printers, basic software, peripherals, and user issues.'],
                    ['title' => 'Network AMC', 'description' => 'Support switches, routers, Wi-Fi, firewalls, VPN, cabling, and connectivity problems.'],
                    ['title' => 'Server AMC', 'description' => 'Monitor and maintain servers, backups, patches, storage, permissions, and performance.'],
                    ['title' => 'CCTV and Access AMC', 'description' => 'Check cameras, recording, power, access devices, locks, and security system health.'],
                    ['title' => 'Preventive Maintenance Visits', 'description' => 'Schedule checks, cleaning, updates, backup reviews, and issue documentation.'],
                    ['title' => 'AMC Reporting', 'description' => 'Share support tickets, recurring issues, asset status, recommendations, and service history.'],
                ],
                'why_points' => [
                    'Single support partner across IT infrastructure, networking, CCTV, and access control',
                    'Preventive checks that reduce surprise downtime and recurring faults',
                    'Clear escalation and response expectations for common business issues',
                    'Support records that help plan replacements, upgrades, and budgets',
                    'Flexible AMC scope for offices, factories, schools, clinics, and retail sites',
                ],
                'faqs' => [
                    ['question' => 'What can be covered under AMC?', 'answer' => 'AMC can cover desktops, laptops, servers, networks, Wi-Fi, CCTV, access control, printers, and related support depending on scope.'],
                    ['question' => 'Do AMC plans include on-site visits?', 'answer' => 'Yes. Plans can include scheduled preventive visits and on-site support based on the agreed scope.'],
                    ['question' => 'Can you take over existing equipment?', 'answer' => 'Yes. We audit current assets, document issues, define coverage, and begin support after agreement.'],
                    ['question' => 'Can AMC reduce downtime?', 'answer' => 'Yes. Preventive checks, faster troubleshooting, monitoring, and documented support reduce many avoidable disruptions.'],
                ],
                'tech_stacks' => ['Dell', 'HP', 'Cisco', 'Hikvision', 'Microsoft', 'Lenovo'],
                'industries' => ['Corporate Offices', 'Manufacturing', 'Healthcare', 'Retail', 'Education', 'Hospitality'],
                'cta_heading' => 'Ready for Dependable AMC Support?',
            ],
        ];
    }
}
