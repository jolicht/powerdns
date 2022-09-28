<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Model;

enum Type: string
{
    case A = 'A';
    case AAAA = 'AAAA';
    case AFSDB = 'AFSDB';
    case ALIAS = 'ALIAS';
    case APL = 'APL';
    case CAA = 'CAA';
    case CERT = 'CERT';
    case CDNSKEY = 'CDNSKEY';
    case CDS = 'CDS';
    case CNAME = 'CNAME';
    case CSYNC = 'CSYNC';
    case DNSKEY = 'DNSKEY';
    case DNAME = 'DNAME';
    case DS = 'DS';
    case HINFO = 'HINFO';
    case HTTPS = 'HTTPS';
    case KEY = 'KEY';
    case LOC = 'LOC';
    case MX = 'MX';
    case NAPTR = 'NAPTR';
    case NS = 'NS';
    case NSEC = 'NSEC';
    case NSEC3 = 'NSEC3';
    case NSEC3PARAM = 'NSEC3PARAM';
    case OPENPGPKEY = 'OPENPGPKEY';
    case PTR = 'PTR';
    case RP = 'RP';
    case RRSIG = 'RRSIG';
    case SOA = 'SOA';
    case SPF = 'SPF';
    case SSHFP = 'SSHFP';
    case SRV = 'SRV';
    case SVCB = 'SVCB';
    case TKEY = 'TKEY';
    case TSIG = 'TSIG';
    case TLSA = 'TLSA';
    case SMIMEA = 'SMIMEA';
    case TXT = 'TXT';
    case URI = 'URI';
    case A6 = 'A6';
    case DHCID = 'DHCID';
    case DLV = 'DLV';
    case EUI48 = 'EUI48';
    case EUI64 = 'EUI64';
    case IPSECKEY = 'IPSECKEY';
    case KX = 'KX';
    case L32 = 'L32';
    case L64 = 'L64';
    case LP = 'LP';
    case MAILA = 'MAILA';
    case MAILB = 'MAILB';
    case MINFO = 'MINFO';
    case MR = 'MR';
    case NID = 'NID';
    case RKEY = 'RKEY';
    case SIG = 'SIG';
    case WKS = 'WKS';
}
